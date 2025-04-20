<?php

namespace Micromus\KafkaBus\Connections\Registry;

use Micromus\KafkaBus\Connections\KafkaConnection;
use Micromus\KafkaBus\Connections\NullConnection;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Exceptions\Connections\DriverException;

class DriverRegistry
{
    protected array $drivers = [];

    public function __construct()
    {
        $this->initDrivers();
    }

    public function add(string $driverName, callable $connectionMaker): void
    {
        $this->drivers[$driverName] = $connectionMaker;
    }

    protected function initDrivers(): void
    {
        $this->addNullDriver();
        $this->addKafkaDriver();
    }

    private function addNullDriver(): void
    {
        $this->add('null', fn ($name, $options) => new NullConnection($name, $options));
    }

    private function addKafkaDriver(): void
    {
        $this->add('kafka', fn ($name, $options) => new KafkaConnection($name, $options));
    }

    public function makeConnection(string $connectionName, string $driverName, array $options): ConnectionInterface
    {
        if (! isset($this->drivers[$driverName])) {
            $availableDrivers = implode(', ', array_keys($this->drivers));

            throw new DriverException("Driver [$driverName] not defined. Available drivers: $availableDrivers");
        }

        return call_user_func($this->drivers[$driverName], $connectionName, $options);
    }
}
