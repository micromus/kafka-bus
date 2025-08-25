<?php

namespace Micromus\KafkaBus\Connections\Registry;

use Micromus\KafkaBus\Connections\Config;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionConfigInterface;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionRegistryInterface;
use Micromus\KafkaBus\Exceptions\Connections\ConnectionException;

class ConnectionRegistry implements ConnectionRegistryInterface
{
    /**
     * @var array<string, ConnectionInterface>
     */
    protected array $activeConnections = [];

    /**
     * @param DriverRegistry $driverRegistry
     * @param array<string, ConnectionConfigInterface> $connectionsConfig
     */
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
        return $this->driverRegistry
            ->makeConnection($connectionName, $this->getConnectionConfig($connectionName));
    }

    private function getConnectionConfig(string $connectionName): ConnectionConfigInterface
    {
        return $this->connectionsConfig[$connectionName]
            ?? throw ConnectionException::connectionNotFound($connectionName, array_keys($this->connectionsConfig));
    }
}
