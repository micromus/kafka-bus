<?php

namespace Micromus\KafkaBus\Consumers;

use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerNotHandledException;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerStreamInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageHandlerInterface;
use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerException;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
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
        protected ConsumerInterface $consumer,
        protected ConsumerMessageHandlerInterface $consumerMessageHandler
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
        }
        while (! $this->forceStop);
    }

    /**
     * @param ConsumerMessageInterface $message
     * @return void
     *
     * @throws MessageConsumerNotHandledException
     */
    private function handleMessage(ConsumerMessageInterface $message): void
    {
        $this->consumerMessageHandler->handle($message);
        $this->consumer->commit($message);
    }

    public function forceStop(): void
    {
        $this->forceStop = true;
    }
}
