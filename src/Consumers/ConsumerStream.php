<?php

namespace Micromus\KafkaBus\Consumers;

use Micromus\KafkaBus\Consumers\Counters\MessageCounter;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouter;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerStreamInterface;
use Micromus\KafkaBus\Interfaces\Messages\MessagePipelineInterface;
use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\MessagesCompletedConsumerException;
use Micromus\KafkaBus\Testing\Exceptions\KafkaMessagesEndedException;

class ConsumerStream implements ConsumerStreamInterface
{
    protected bool $forceStop = false;

    private const IGNORABLE_CONSUMER_ERRORS = [
        RD_KAFKA_RESP_ERR__PARTITION_EOF,
        RD_KAFKA_RESP_ERR__TRANSPORT,
        RD_KAFKA_RESP_ERR_REQUEST_TIMED_OUT,
        RD_KAFKA_RESP_ERR__TIMED_OUT,
    ];

    public function __construct(
        protected ConsumerInterface        $consumer,
        protected ConsumerRouter           $consumerRouter,
        protected MessagePipelineInterface $messagePipeline,
        protected MessageCounter           $messageCounter = new MessageCounter()
    ) {
    }

    public function listen(): void
    {
        do {
            try {
                $consumerMessage = $this->consumer
                    ->getMessage();

                $this->handleMessage($consumerMessage);
            }
            catch (MessageConsumerException $exception) {
                if (! in_array($exception->consumerMessage->err, self::IGNORABLE_CONSUMER_ERRORS, true)) {
                    throw $exception;
                }
            }
            catch (KafkaMessagesEndedException) {
                return;
            }

            if ($this->messageCounter->isCompleted()) {
                throw new MessagesCompletedConsumerException('Превышено количество прочитанных сообщений');
            }
        }
        while (! $this->forceStop);
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

    public function forceStop(): void
    {
        $this->forceStop = true;
    }
}
