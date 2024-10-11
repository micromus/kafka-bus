<?php

namespace Micromus\KafkaBus\Connections\Registry;

use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionRegistryInterface;
use Micromus\KafkaBus\Exceptions\Connections\ConnectionException;

class ConnectionRegistry implements ConnectionRegistryInterface
{
    protected array $activeConnections = [];

    public function __construct(
        protected DriverRegistry $driverRegistry,
        protected array $connectionsConfig
    ) {
    }

    public function connection(string $connectionName): ConnectionInterface
    {
        if (! isset($this->activeConnections[$connectionName])) {
            $this->activeConnections[$connectionName] = $this->makeConnection($connectionName);
        }

        return $this->activeConnections[$connectionName];
    }

    private function makeConnection(string $connectionName): ConnectionInterface
    {
        $config = $this->getConnectionConfig($connectionName);

        return $this->driverRegistry->makeConnection($config['driver'], $config['options'] ?: []);
    }

    private function getConnectionConfig(string $connectionName): array
    {
        if (! isset($this->connectionsConfig[$connectionName])) {
            $availableConnections = implode(', ', array_keys($this->connectionsConfig));

            throw new ConnectionException(
                "Connection [$connectionName] not defined." .
                    " Available connections: $availableConnections"
            );
        }

        return $this->connectionsConfig[$connectionName];
    }
}
