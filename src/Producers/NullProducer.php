<?php

namespace Micromus\KafkaBus\Producers;

use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;

class NullProducer implements ProducerInterface
{
    public function produce(array $messages): void
    {
    }
}
