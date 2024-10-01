<?php

namespace Micromus\KafkaBus\Consumers;

use Micromus\KafkaBus\Consumers\Counters\MessageCounter;
use Micromus\KafkaBus\Consumers\Counters\Timer;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouter;
use Micromus\KafkaBus\Contracts\Consumers\Consumer as ConsumerContract;
use Micromus\KafkaBus\Contracts\Consumers\ConsumerStream as ConsumerStreamContract;
use Micromus\KafkaBus\Contracts\Messages\MessagePipeline;
use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\MessagesCompletedConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\TimeoutConsumerException;
use Micromus\KafkaBus\Testing\Exceptions\KafkaMessagesEndedException;

class ConsumerStream implements ConsumerStreamContract
{
    protected bool $forceStop = false;

    private const IGNORABLE_CONSUMER_ERRORS = [
        RD_KAFKA_RESP_ERR__PARTITION_EOF,
        RD_KAFKA_RESP_ERR__TRANSPORT,
        RD_KAFKA_RESP_ERR_REQUEST_TIMED_OUT,
        RD_KAFKA_RESP_ERR__TIMED_OUT,
    ];

    private const CONSUME_STOP_EOF_ERRORS = [
        RD_KAFKA_RESP_ERR__PARTITION_EOF,
        RD_KAFKA_RESP_ERR__TIMED_OUT,
    ];

    public function __construct(
        protected ConsumerContract $consumer,
        protected ConsumerRouter $consumerRouter,
        protected MessagePipeline $messagePipeline,
        protected MessageCounter $messageCounter = new MessageCounter,
        protected Timer $timer = new Timer
    ) {}

    public function listen(): void
    {
        $this->timer->start();
        $this->listenForSignals();

        try {
            do {
                $consumerMessage = $this->consumer
                    ->getMessage();

                if ($consumerMessage->meta->message->err !== RD_KAFKA_RESP_ERR_NO_ERROR) {
                    if (in_array($consumerMessage->meta->message->err, self::CONSUME_STOP_EOF_ERRORS, true)) {
                        return;
                    }

                    if (! in_array($consumerMessage->meta->message->err, self::IGNORABLE_CONSUMER_ERRORS, true)) {
                        throw new MessageConsumerException($consumerMessage->meta->message);
                    }
                }

                $this->handleMessage($consumerMessage);

                if ($this->timer->isTimeout()) {
                    throw new TimeoutConsumerException('Время прослушивания закончилось');
                }

                if ($this->messageCounter->isCompleted()) {
                    throw new MessagesCompletedConsumerException('Превышено количество прочитанных сообщений');
                }
            } while (! $this->forceStop);
        } catch (KafkaMessagesEndedException) {
        }
    }

    private function handleMessage(ConsumerMessage $message): void
    {
        $this->messagePipeline
            ->then($message, function (ConsumerMessage $message) {
                $this->consumerRouter
                    ->handle($message);

                return $message;
            });

        $this->consumer->commit($message);

        $this->messageCounter->increment();
    }

    private function forceStop(): void
    {
        $this->forceStop = true;
    }

    private function listenForSignals(): void
    {
        pcntl_async_signals(true);

        pcntl_signal(SIGQUIT, fn () => $this->forceStop());
        pcntl_signal(SIGTERM, fn () => $this->forceStop());
        pcntl_signal(SIGINT, fn () => $this->forceStop());
    }
}
