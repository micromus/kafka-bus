<?php

namespace Micromus\KafkaBus\Consumers\Commiters;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use RdKafka\KafkaConsumer;

class DefaultCommiter implements CommiterInterface
{
    public function __construct(
        protected KafkaConsumer $consumer
    ) {
    }

    public function commit(ConsumerMessage $consumerMessage): void
    {
        $this->consumer
            ->commitAsync($consumerMessage->meta->message);
    }
}
