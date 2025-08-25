<?php

namespace Micromus\KafkaBus\Interfaces\Producers;

use Micromus\KafkaBus\Bus\Publishers\Router\Route;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;

interface ProducerStreamFactoryInterface
{
    /**
     * @template TMessage of ProducerMessageInterface
     *
     * @param ConnectionInterface $connection
     * @param Route<TMessage> $route
     * @return ProducerStreamInterface<TMessage>
     */
    public function create(ConnectionInterface $connection, Route $route): ProducerStreamInterface;
}
