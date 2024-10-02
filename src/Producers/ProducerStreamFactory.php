<?php

namespace Micromus\KafkaBus\Producers;

use Micromus\KafkaBus\Bus\Publishers\Options;
use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Messages\MessagePipelineFactory;
use Micromus\KafkaBus\Contracts\Producers\ProducerStream as ProducerStreamContract;
use Micromus\KafkaBus\Contracts\Producers\ProducerStreamFactory as ProducerStreamFactoryContract;
use Micromus\KafkaBus\Topics\Topic;

class ProducerStreamFactory implements ProducerStreamFactoryContract
{
    public function __construct(
        protected MessagePipelineFactory $messagePipelineFactory
    ) {}

    public function create(Connection $connection, Topic $topic, Options $options): ProducerStreamContract
    {
        $configuration = $this->makeProducerConfiguration($options);

        return new ProducerStream(
            $connection->createProducer($topic->name, $configuration),
            $this->messagePipelineFactory->create($options->middlewares),
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
