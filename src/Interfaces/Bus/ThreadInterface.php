<?php

namespace Micromus\KafkaBus\Interfaces\Bus;

use Micromus\KafkaBus\Bus\Listeners\Listener;
use Micromus\KafkaBus\Exceptions\Producers\RouteProducerException;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;

interface ThreadInterface
{
    /**
     * @param  iterable<ProducerMessageInterface>  $messages
     *
     * @throws RouteProducerException
     */
    public function publish(iterable $messages): void;

    public function createListener(string $listenerWorkerName): Listener;
}
