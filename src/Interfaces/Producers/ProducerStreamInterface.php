<?php

namespace Micromus\KafkaBus\Interfaces\Producers;

use Micromus\KafkaBus\Interfaces\Messages\MessageInterface;

interface ProducerStreamInterface
{
    /**
     * @param  MessageInterface[]  $messages
     */
    public function handle(array $messages): void;
}
