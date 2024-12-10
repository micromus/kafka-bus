<?php

namespace Micromus\KafkaBus\Testing;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessageConverter;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Testing\Connections\ConnectionFaker;
use Micromus\KafkaBus\Testing\Exceptions\KafkaMessagesEndedException;

class ConsumerFaker implements ConsumerInterface
{
    public function __construct(
        protected ConnectionFaker          $connectionFaker,
        protected ConsumerMessageConverter $consumerMessageConverter,
        protected array                    $messages
    ) {
    }

    public function getMessage(): ConsumerMessageInterface
    {
        if (count($this->messages) == 0) {
            throw new KafkaMessagesEndedException();
        }

        return $this->consumerMessageConverter
            ->fromKafka(array_shift($this->messages));
    }

    public function commit(ConsumerMessageInterface $consumerMessage): void
    {
        $this->connectionFaker->committedMessages[$consumerMessage->topicName()][] = $consumerMessage;
    }
}
