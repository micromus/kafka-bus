<?php

namespace Micromus\KafkaBus;

use Micromus\KafkaBus\Bus\ThreadRegistry;
use Micromus\KafkaBus\Contracts\Bus\Bus as BusContract;
use Micromus\KafkaBus\Contracts\Bus\Thread;
use Micromus\KafkaBus\Contracts\Messages\Message;

class Bus implements BusContract
{
    protected Thread $thread;

    public function __construct(
        protected ThreadRegistry $threadRegistry,
        string $defaultConnection
    ) {
        $this->thread = $this->threadRegistry->thread($defaultConnection);
    }

    public function onConnection(string $connectionName): Thread
    {
        return $this->threadRegistry
            ->thread($connectionName);
    }

    public function publish(Message $message): void
    {
        $this->thread->publish($message);
    }

    public function publishMany(array $messages): void
    {
        $this->thread->publishMany($messages);
    }

    public function listen(string $listenerWorkerName): void
    {
        $this->thread->listen($listenerWorkerName);
    }
}
