<?php

namespace Micromus\KafkaBus\Contracts\Connections;

interface ConnectionRegistry
{
    public function connection(?string $connectionName = null): Connection;
}
