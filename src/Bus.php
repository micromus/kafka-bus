<?php

namespace Micromus\KafkaBus;

use Micromus\KafkaBus\Bus\Listeners\Listener;
use Micromus\KafkaBus\Bus\ThreadRegistry;
use Micromus\KafkaBus\Interfaces\Bus\BusInterface;
use Micromus\KafkaBus\Interfaces\Bus\ThreadInterface;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;

class Bus implements BusInterface
{
    protected ThreadInterface $thread;

    public function __construct(
        protected ThreadRegistry $threadRegistry,
        string $defaultConnection
    ) {
        $this->thread = $this->threadRegistry->thread($defaultConnection);
    }

    public function onConnection(string $connectionName): ThreadInterface
    {
        return $this->threadRegistry
            ->thread($connectionName);
    }

    public function publish(iterable $messages): void
    {
        $this->thread->publish($messages);
    }

    public function createListener(string $listenerWorkerName): Listener
    {
        return $this->thread->createListener($listenerWorkerName);
    }
}
