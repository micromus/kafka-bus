<?php

namespace Micromus\KafkaBus\Bus\Listeners;

use Micromus\KafkaBus\Bus\Listeners\Workers\WorkerRegistry;
use Micromus\KafkaBus\Exceptions\Listeners\ListenerException;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerStreamFactoryInterface;

class ListenerFactory
{
    public function __construct(
        protected ConsumerStreamFactoryInterface $streamFactory,
        protected WorkerRegistry $workerRegistry = new WorkerRegistry(),
    ) {
    }

    public function create(ConnectionInterface $connection, string $listenerWorkerName): Listener
    {
        $worker = $this->workerRegistry->get($listenerWorkerName)
            ?? throw new ListenerException("Worker [$listenerWorkerName] not found.");

        $consumerStream = $this->streamFactory->create($connection, $worker);

        return new Listener($worker, $connection, $consumerStream);
    }
}
