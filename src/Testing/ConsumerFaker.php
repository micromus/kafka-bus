<?php

namespace Micromus\KafkaBus\Testing;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessageConverter;
use Micromus\KafkaBus\Contracts\Consumers\Consumer;
use Micromus\KafkaBus\Testing\Exceptions\KafkaMessagesEndedException;

class ConsumerFaker implements Consumer
{
    public function __construct(
        protected ConnectionFaker $connectionFaker,
        protected ConsumerMessageConverter $consumerMessageConverter,
        protected array $messages
    ) {}

    public function getMessage(): ConsumerMessage
    {
        if (count($this->messages) == 0) {
            throw new KafkaMessagesEndedException;
        }

        return $this->consumerMessageConverter
            ->fromKafka(array_shift($this->messages));
    }

    public function commit(ConsumerMessage $consumerMessage): void
    {
        $this->connectionFaker->committedMessages[$consumerMessage->topicName()][] = $consumerMessage;
    }
}
