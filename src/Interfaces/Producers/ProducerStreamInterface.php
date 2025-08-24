<?php

namespace Micromus\KafkaBus\Interfaces\Producers;

use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;

/**
 * @template TMessage of ProducerMessageInterface = mixed
 */
interface ProducerStreamInterface
{
    /**
     * @param iterable<TMessage> $messages
     */
    public function handle(iterable $messages): void;
}
