<?php

namespace Micromus\KafkaBus\Producers;

class NullProducer implements \Micromus\KafkaBus\Contracts\Producers\Producer
{
    public function produce(array $messages): void
    {
    }
}
