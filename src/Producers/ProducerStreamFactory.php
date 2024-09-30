<?php

namespace Micromus\KafkaBus\Producers;

use Micromus\KafkaBus\Bus\Publishers\Options;
use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Messages\MessagePipelineFactory;
use Micromus\KafkaBus\Contracts\Producers\ProducerStream as ProducerStreamContract;
use Micromus\KafkaBus\Contracts\Producers\ProducerStreamFactory as ProducerStreamFactoryContract;
use Micromus\KafkaBus\Contracts\TopicNameResolver;

class ProducerStreamFactory implements ProducerStreamFactoryContract
{
    public function __construct(
        protected MessagePipelineFactory $messagePipelineFactory,
        protected TopicNameResolver $topicNameResolver
    ) {}

    public function create(Connection $connection, string $topicKey, Options $options): ProducerStreamContract
    {
        $configuration = $this->makeProducerConfiguration($options);
        $topicName = $this->topicNameResolver->resolve($topicKey);

        return new ProducerStream(
            $connection->createProducer($topicName, $configuration),
            $this->messagePipelineFactory->create($options->middlewares)
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
