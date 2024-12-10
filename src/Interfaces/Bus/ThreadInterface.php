<?php

namespace Micromus\KafkaBus\Interfaces\Bus;

use Micromus\KafkaBus\Bus\Listeners\Listener;
use Micromus\KafkaBus\Exceptions\Producers\RouteProducerException;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;

interface ThreadInterface
{
    /**
     * @throws RouteProducerException
     */
    public function publish(ProducerMessageInterface $message): void;

    /**
     * @param  ProducerMessageInterface[]  $messages
     *
     * @throws RouteProducerException
     */
    public function publishMany(array $messages): void;

    public function listener(string $listenerWorkerName): Listener;
}
