<?php

namespace Micromus\KafkaBus\Bus\Listeners;

use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;
use Micromus\KafkaBus\Bus\Listeners\Workers\WorkerRegistry;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerStreamFactoryInterface;
use Micromus\KafkaBus\Exceptions\Consumers\ListenerException;

class ListenerFactory
{
    public function __construct(
        protected ConsumerStreamFactoryInterface $streamFactory,
        protected WorkerRegistry                 $workerRegistry = new WorkerRegistry(),
    ) {
    }

    public function create(ConnectionInterface $connection, string $listenerWorkerName): Listener
    {
        $worker = $this->getWorker($listenerWorkerName);

        return new Listener($this->streamFactory->create($connection, $worker));
    }

    private function getWorker(string $workerName): Worker
    {
        return $this->workerRegistry->get($workerName)
            ?: throw new ListenerException("Worker [$workerName] not found.");
    }
}
