<?php

namespace Micromus\KafkaBus\Interfaces\Connections;

interface ConnectionRegistryInterface
{
    public function connection(string $connectionName): ConnectionInterface;
}
