<?php

namespace Micromus\KafkaBus\Interfaces\Bus;

use Micromus\KafkaBus\Bus\Listeners\Listener;
use Micromus\KafkaBus\Bus\Publishers\Router\Route;
use Micromus\KafkaBus\Exceptions\Producers\RouteProducerException;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;

interface ThreadInterface
{
    /**
     * @return list<Route>
     */
    public function routes(): array;

    /**
     * @param  iterable<ProducerMessageInterface>  $messages
     *
     * @throws RouteProducerException
     */
    public function publish(iterable $messages): void;

    public function listener(string $listenerWorkerName): Listener;
}
