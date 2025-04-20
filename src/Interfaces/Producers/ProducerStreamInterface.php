<?php

namespace Micromus\KafkaBus\Interfaces\Producers;

use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;

/**
 * @template TMessage of ProducerMessageInterface
 */
interface ProducerStreamInterface
{
    /**
     * @param non-empty-list<TMessage> $messages
     */
    public function handle(array $messages): void;
}
