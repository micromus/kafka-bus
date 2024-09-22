<?php

namespace Micromus\KafkaBus\Producers;

use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Messages\MessagePipelineFactory;
use Micromus\KafkaBus\Contracts\Producers\ProducerStream as ProducerStreamContract;
use Micromus\KafkaBus\Contracts\Producers\ProducerStreamFactory as ProducerStreamFactoryContract;
use Micromus\KafkaBus\Contracts\TopicNameResolver;

class ProducerStreamFactory implements ProducerStreamFactoryContract
{
    public function __construct(
        protected TopicNameResolver $topicNameResolver,
        protected MessagePipelineFactory $messagePipelineFactory,
        protected array $producerConfiguration = []
    ) {}

    public function create(Connection $connection, string $topicKey, array $options = []): ProducerStreamContract
    {
        $rawConfiguration = $this->rawConfiguration($options);
        $configuration = $this->makeProducerConfiguration($rawConfiguration);
        $topicName = $this->topicNameResolver->resolve($topicKey);

        return new ProducerStream(
            $connection->createProducer($topicName, $configuration),
            $this->messagePipelineFactory->create($rawConfiguration['middlewares'])
        );
    }

    private function rawConfiguration(array $options): array
    {
        return [
            ...$this->producerConfiguration,
            ...$options,

            'middlewares' => [
                ...($this->producerConfiguration['middlewares'] ?? []),
                ...($options['middlewares'] ?? []),
            ],
        ];
    }

    private function makeProducerConfiguration(array $rawConfiguration): ProducerConfiguration
    {
        return new ProducerConfiguration(
            compression: $rawConfiguration['compression'] ?? 'snappy',
            flushTimeout: $rawConfiguration['flush_timeout'] ?? 5000,
            flushRetries: $rawConfiguration['flush_retries'] ?? 5
        );
    }
}
