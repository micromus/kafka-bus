<?php

namespace Micromus\KafkaBus\Interfaces\Bus;

interface BusInterface extends ThreadInterface
{
    public function onConnection(string $connectionName): ThreadInterface;
}
