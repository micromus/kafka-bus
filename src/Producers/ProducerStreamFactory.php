<?php

namespace Micromus\KafkaBus\Producers;

use Micromus\KafkaBus\Bus\Publishers\Router\Options;
use Micromus\KafkaBus\Bus\Publishers\Router\Route;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerStreamInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerStreamFactoryInterface;

class ProducerStreamFactory implements ProducerStreamFactoryInterface
{
    public function create(ConnectionInterface $connection, Route $route): ProducerStreamInterface
    {
        $configuration = $this->makeProducerConfiguration($route->options);

        return new ProducerStream($route, $connection->createProducer($route->topic, $configuration));
    }

    private function makeProducerConfiguration(Options $options): ProducerConfig
    {
        return new ProducerConfig(
            additionalOptions: $options->additionalOptions,
            flushTimeout: $options->flushTimeout,
            flushRetries: $options->flushRetries,
        );
    }
}
