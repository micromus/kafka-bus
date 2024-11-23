<?php

namespace Micromus\KafkaBus\Consumers;

use Micromus\KafkaBus\Consumers\Counters\MessageCounter;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerNotHandledException;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerStreamInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageHandlerInterface;
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
        protected ConsumerMessageHandlerInterface $consumerMessageHandler,
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
            // @phpstan-ignore-next-line
            catch (KafkaMessagesEndedException) {
                return;
            }

            if ($this->messageCounter->isCompleted()) {
                throw new MessagesCompletedConsumerException('Превышено количество прочитанных сообщений');
            }
        }
        while (! $this->forceStop);
    }

    /**
     * @param ConsumerMessage $message
     * @return void
     *
     * @throws MessageConsumerNotHandledException
     */
    private function handleMessage(ConsumerMessage $message): void
    {
        $this->consumerMessageHandler->handle($message);
        $this->consumer->commit($message);

        $this->messageCounter->increment();
    }

    public function forceStop(): void
    {
        $this->forceStop = true;
    }
}
