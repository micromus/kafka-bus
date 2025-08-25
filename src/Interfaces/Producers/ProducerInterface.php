<?php

namespace Micromus\KafkaBus\Interfaces\Producers;

use Micromus\KafkaBus\Producers\Messages\ProducerMessage;

interface ProducerInterface
{
    /**
     * @param iterable<ProducerMessage> $messages
     */
    public function produce(iterable $messages): void;
}
