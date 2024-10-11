<?php

namespace Micromus\KafkaBus\Testing\Connections;

use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionRegistryInterface;

class ConnectionRegistryFaker implements ConnectionRegistryInterface
{
    public function __construct(
        protected ConnectionInterface $connection
    ) {
    }

    public function connection(?string $connectionName = null): ConnectionInterface
    {
        return $this->connection;
    }
}
