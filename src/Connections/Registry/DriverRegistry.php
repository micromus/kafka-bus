<?php

namespace Micromus\KafkaBus\Connections\Registry;

use Micromus\KafkaBus\Connections\Config\KafkaConnectionConfig;
use Micromus\KafkaBus\Connections\Config\NullConnectionConfig;
use Micromus\KafkaBus\Connections\KafkaConnection;
use Micromus\KafkaBus\Connections\NullConnection;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionConfigInterface;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Exceptions\Connections\DriverException;

class DriverRegistry
{
    /**
     * @var array<class-string, callable(string, ConnectionConfigInterface): ConnectionInterface>
     */
    protected array $drivers = [];

    public function __construct()
    {
        $this->initDrivers();
    }

    /**
     * @template TConfiguration of ConnectionConfigInterface
     * @param class-string<TConfiguration> $configurationClass
     * @param callable(string, TConfiguration): ConnectionInterface $connectionMaker
     * @return void
     */
    public function add(string $configurationClass, callable $connectionMaker): void
    {
        $this->drivers[$configurationClass] = $connectionMaker; // @phpstan-ignore-line
    }

    protected function initDrivers(): void
    {
        $this->addNullDriver();
        $this->addKafkaDriver();
    }

    private function addNullDriver(): void
    {
        $this->add(
            NullConnectionConfig::class,
            static fn (string $name, NullConnectionConfig $config) => new NullConnection($name)
        );

    }

    private function addKafkaDriver(): void
    {
        $this->add(
            KafkaConnectionConfig::class,
            static fn (string $name, KafkaConnectionConfig $config) => new KafkaConnection($name, $config->getOptions())
        );
    }

    public function makeConnection(string $name, ConnectionConfigInterface $config): ConnectionInterface
    {
        $configuration = get_class($config);

        $driver = $this->drivers[$configuration]
            ?? throw DriverException::driverNotFound($configuration, array_keys($this->drivers));

        return call_user_func($driver, $name, $config);
    }
}
