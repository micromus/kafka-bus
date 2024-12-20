<?php

namespace Micromus\KafkaBus\Producers;

use Micromus\KafkaBus\Bus\Publishers\Router\Options;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineFactoryInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerStreamInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerStreamFactoryInterface;
use Micromus\KafkaBus\Topics\Topic;

class ProducerStreamFactory implements ProducerStreamFactoryInterface
{
    public function __construct(
        protected PipelineFactoryInterface $pipelineFactory
    ) {
    }

    public function create(ConnectionInterface $connection, Topic $topic, Options $options): ProducerStreamInterface
    {
        $configuration = $this->makeProducerConfiguration($options);

        return new ProducerStream(
            $connection->createProducer($topic->name, $configuration),
            $this->pipelineFactory->create($options->middlewares),
            $topic
        );
    }

    private function makeProducerConfiguration(Options $options): Configuration
    {
        return new Configuration(
            additionalOptions: $options->additionalOptions,
            flushTimeout: $options->flushTimeout,
            flushRetries: $options->flushRetries,
        );
    }
}
