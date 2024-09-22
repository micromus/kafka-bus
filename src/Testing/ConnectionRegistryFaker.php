<?php

namespace Micromus\KafkaBus\Testing;

use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Connections\ConnectionRegistry;

class ConnectionRegistryFaker implements ConnectionRegistry
{
    public function __construct(
        protected Connection $connection
    ) {
    }

    public function connection(?string $connectionName = null): Connection
    {
        return $this->connection;
    }
}
