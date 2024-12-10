<?php

namespace Micromus\KafkaBus\Interfaces\Producers;

use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;

interface ProducerStreamInterface
{
    /**
     * @param  ProducerMessageInterface[]  $messages
     */
    public function handle(array $messages): void;
}
