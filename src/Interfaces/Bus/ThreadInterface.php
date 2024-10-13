<?php

namespace Micromus\KafkaBus\Interfaces\Bus;

use Micromus\KafkaBus\Bus\Listeners\Listener;
use Micromus\KafkaBus\Interfaces\Messages\MessageInterface;
use Micromus\KafkaBus\Exceptions\Producers\RouteProducerException;

interface ThreadInterface
{
    /**
     * @throws RouteProducerException
     */
    public function publish(MessageInterface $message): void;

    /**
     * @param  MessageInterface[]  $messages
     *
     * @throws RouteProducerException
     */
    public function publishMany(array $messages): void;

    public function listener(string $listenerWorkerName): Listener;
}
