<?php

namespace Micromus\KafkaBus\Bus;

use Micromus\KafkaBus\Bus\Listeners\ListenerFactory;
use Micromus\KafkaBus\Bus\Publishers\PublisherFactory;
use Micromus\KafkaBus\Interfaces\Bus\ThreadInterface;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionRegistryInterface;

class ThreadRegistry
{
    protected array $activeThreads = [];

    public function __construct(
        protected ConnectionRegistryInterface $connectionRegistry,
        protected PublisherFactory            $publisherFactory,
        protected ListenerFactory             $listenerFactory
    ) {
    }

    public function thread(string $connectionName): ThreadInterface
    {
        if (! isset($this->activeThreads[$connectionName])) {
            $this->activeThreads[$connectionName] = $this->makeThread($connectionName);
        }

        return $this->activeThreads[$connectionName];
    }

    private function makeThread(string $connectionName): ThreadInterface
    {
        $connection = $this->connectionRegistry
            ->connection($connectionName);

        return new Thread(
            $connection,
            $this->listenerFactory,
            $this->publisherFactory
        );
    }
}
