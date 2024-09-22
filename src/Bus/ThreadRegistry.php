<?php

namespace Micromus\KafkaBus\Bus;

use Micromus\KafkaBus\Contracts\Bus\Thread as ThreadContract;
use Micromus\KafkaBus\Contracts\Connections\ConnectionRegistry;
use Micromus\KafkaBus\Contracts\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Producers\Router\ProducerRouterFactory;

class ThreadRegistry
{
    protected array $activeThreads = [];

    public function __construct(
        protected ConnectionRegistry $connectionRegistry,
        protected ProducerRouterFactory $producerRouterFactory,
        protected ConsumerStreamFactory $consumerStreamFactory
    ) {}

    public function thread(string $connectionName): ThreadContract
    {
        if (! isset($this->activeThreads[$connectionName])) {
            $this->activeThreads[$connectionName] = $this->makeThread($connectionName);
        }

        return $this->activeThreads[$connectionName];
    }

    /**
     * @param string $connectionName
     * @return ThreadContract
     */
    private function makeThread(string $connectionName): ThreadContract
    {
        $connection = $this->connectionRegistry
            ->connection($connectionName);

        return new Thread(
            $connection,
            $this->producerRouterFactory->create($connection),
            $this->consumerStreamFactory
        );
    }
}
