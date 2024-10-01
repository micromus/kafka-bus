<?php

namespace Micromus\KafkaBus\Consumers\Commiters;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use RdKafka\KafkaConsumer;

class DefaultCommiter implements Commiter
{
    public function __construct(
        protected KafkaConsumer $consumer
    ) {}

    public function commit(ConsumerMessage $consumerMessage): void
    {
        $this->consumer
            ->commit($consumerMessage->meta->message);
    }
}
