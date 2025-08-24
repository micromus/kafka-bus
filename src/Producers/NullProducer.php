<?php

namespace Micromus\KafkaBus\Producers;

use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;

final class NullProducer implements ProducerInterface
{
    public function produce(iterable $messages): void
    {
    }
}
