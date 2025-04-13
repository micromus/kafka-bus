<?php

namespace Micromus\KafkaBus\Connections;

use Micromus\KafkaBus\Connections\Kafka\KafkaConsumerFactory;
use Micromus\KafkaBus\Connections\Kafka\KafkaOffsetSetterFactory;
use Micromus\KafkaBus\Connections\Kafka\KafkaProducerFactory;
use Micromus\KafkaBus\Connections\Offsets\Offset;
use Micromus\KafkaBus\Connections\Offsets\OffsetConnectionSetter;
use Micromus\KafkaBus\Consumers\Commiters\DefaultCommiter;
use Micromus\KafkaBus\Consumers\Commiters\VoidCommiter;
use Micromus\KafkaBus\Consumers\Consumer;
use Micromus\KafkaBus\Consumers\ConsumerConfig;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionOffsetInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;
use Micromus\KafkaBus\Producers\Producer;
use Micromus\KafkaBus\Producers\ProducerConfig;
use Micromus\KafkaBus\Support\RetryRepeater;
use Micromus\KafkaBus\Topics\Partition;
use Micromus\KafkaBus\Topics\Topic;

class KafkaConnection implements ConnectionInterface, ConnectionOffsetInterface
{
    protected KafkaConnectionConfig $connectionConfig;

    protected KafkaProducerFactory $producerFactory;

    protected KafkaConsumerFactory $consumerFactory;

    protected KafkaOffsetSetterFactory $offsetSetterFactory;

    public function __construct(array $options)
    {
        $this->connectionConfig = new KafkaConnectionConfig($options);
        $this->producerFactory = new KafkaProducerFactory($this->connectionConfig);
        $this->consumerFactory = new KafkaConsumerFactory($this->connectionConfig);
        $this->offsetSetterFactory = new KafkaOffsetSetterFactory($this->consumerFactory);
    }

    public function createProducer(Topic $topic, ProducerConfig $config): ProducerInterface
    {
        return new Producer(
            producer: $this->producerFactory->make($config),
            topicName: $topic->name,
            retryRepeater: new RetryRepeater($config->flushRetries),
            timeout: $config->flushTimeout
        );
    }

    public function createConsumer(array $topics, ConsumerConfig $config): ConsumerInterface
    {
        $consumer = $this->consumerFactory->make($config);

        return new Consumer(
            consumer: $consumer,
            topicNames: array_map(fn (Topic $topic) => $topic->name, $topics),
            commiter: $config->autoCommit ? new DefaultCommiter($consumer) : new VoidCommiter(),
            retryRepeater: new RetryRepeater(),
            consumerTimeout: $config->consumerTimeout,
        );
    }

    public function setOffset(Partition $partition, int|Offset $offset, ConsumerConfig $config): array
    {
        return $this->offsetSetterFactory->make($config)
            ->set($partition, $offset);
    }
}
