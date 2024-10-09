<?php

namespace Micromus\KafkaBus\Bus\Listeners;

use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;
use Micromus\KafkaBus\Bus\Listeners\Workers\WorkerRegistry;
use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Exceptions\Consumers\ListenerException;

class ListenerFactory
{
    public function __construct(
        protected ConsumerStreamFactory $streamFactory,
        protected WorkerRegistry $groupRegistry = new WorkerRegistry,
    ) {}

    public function create(Connection $connection, string $listenerGroupName): Listener
    {
        $group = $this->getGroup($listenerGroupName);

        return new Listener(
            $connection,
            $this->streamFactory,
            $group
        );
    }

    private function getGroup(string $groupName): Worker
    {
        return $this->groupRegistry->get($groupName)
            ?: throw new ListenerException("Group [$groupName] not found.");
    }
}
