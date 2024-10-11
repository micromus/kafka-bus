<?php

namespace Micromus\KafkaBus\Interfaces\Producers;

use Micromus\KafkaBus\Producers\Messages\ProducerMessage;

interface ProducerInterface
{
    /**
     * @param  ProducerMessage[]  $messages
     */
    public function produce(array $messages): void;
}
