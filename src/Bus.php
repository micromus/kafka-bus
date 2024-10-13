<?php

namespace Micromus\KafkaBus;

use Micromus\KafkaBus\Bus\Listeners\Listener;
use Micromus\KafkaBus\Bus\ThreadRegistry;
use Micromus\KafkaBus\Interfaces\Bus\BusInterface;
use Micromus\KafkaBus\Interfaces\Bus\ThreadInterface;
use Micromus\KafkaBus\Interfaces\Messages\MessageInterface;

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

    public function publish(MessageInterface $message): void
    {
        $this->thread->publish($message);
    }

    public function publishMany(array $messages): void
    {
        $this->thread->publishMany($messages);
    }

    public function listener(string $listenerWorkerName): Listener
    {
        return $this->thread->listener($listenerWorkerName);
    }
}
