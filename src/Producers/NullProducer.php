<?php

namespace Micromus\KafkaBus\Producers;

use Micromus\KafkaBus\Contracts\Producers\Producer as ProducerContract;

class NullProducer implements ProducerContract
{
    public function produce(array $messages): void {}
}
