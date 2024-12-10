<?php

namespace Micromus\KafkaBus\Consumers\Commiters;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use RdKafka\KafkaConsumer;

class DefaultCommiter implements CommiterInterface
{
    public function __construct(
        protected KafkaConsumer $consumer
    ) {
    }

    public function commit(ConsumerMessageInterface $consumerMessage): void
    {
        $this->consumer
            ->commitAsync($consumerMessage->original());
    }
}
