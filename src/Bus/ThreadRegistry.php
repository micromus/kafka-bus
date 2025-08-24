<?php

namespace Micromus\KafkaBus\Bus;

use Micromus\KafkaBus\Bus\Listeners\ListenerFactory;
use Micromus\KafkaBus\Bus\Publishers\PublisherFactory;
use Micromus\KafkaBus\Interfaces\Bus\ThreadInterface;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionRegistryInterface;

class ThreadRegistry
{
    /**
     * @var array<string, ThreadInterface>
     */
    protected array $activeThreads = [];

    public function __construct(
        protected ConnectionRegistryInterface $connectionRegistry,
        protected ThreadFactory $factory,
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

        return $this->factory->create($connection);
    }
}
