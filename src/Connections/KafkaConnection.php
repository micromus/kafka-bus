<?php

namespace Micromus\KafkaBus\Connections;

use Micromus\KafkaBus\Connections\Config\Options;
use Micromus\KafkaBus\Connections\Kafka\KafkaConsumerFactory;
use Micromus\KafkaBus\Connections\Kafka\KafkaProducerFactory;
use Micromus\KafkaBus\Connections\Topics\Topics;
use Micromus\KafkaBus\Consumers\Commiters\DefaultCommiter;
use Micromus\KafkaBus\Consumers\Commiters\VoidCommiter;
use Micromus\KafkaBus\Consumers\Consumer;
use Micromus\KafkaBus\Consumers\ConsumerConfig;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionHasTopicsInterface;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Connections\Topics\ConnectionTopicsInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;
use Micromus\KafkaBus\Producers\Producer;
use Micromus\KafkaBus\Producers\ProducerConfig;
use Micromus\KafkaBus\Support\RetryRepeater;
use Micromus\KafkaBus\Topics\Topic;

class KafkaConnection implements
    ConnectionInterface,
    ConnectionHasTopicsInterface
{
    protected KafkaProducerFactory $producerFactory;

    protected KafkaConsumerFactory $consumerFactory;

    /**
     * @param string $name
     * @param Options $options
     */
    public function __construct(protected string $name, protected Options $options)
    {
        $this->producerFactory = new KafkaProducerFactory($this->options);
        $this->consumerFactory = new KafkaConsumerFactory($this->options);
    }

    public function createProducer(Topic $topic, ProducerConfig $config): ProducerInterface
    {
        return new Producer(
            producer: $this->producerFactory->make($config),
            topic: $topic,
            retryRepeater: new RetryRepeater($config->flushRetries),
            timeout: $config->flushTimeout
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOptions(): Options
    {
        return $this->options;
    }

    public function createConsumer(array $topics, ConsumerConfig $config): ConsumerInterface
    {
        $consumer = $this->consumerFactory->make($config);

        return new Consumer(
            consumer: $consumer,
            topicNames: array_column($topics, 'name'),
            commiter: $config->autoCommit ? new DefaultCommiter($consumer) : new VoidCommiter(),
            retryRepeater: new RetryRepeater(),
            consumerTimeout: $config->consumerTimeout,
        );
    }

    public function topics(): ConnectionTopicsInterface
    {
        return new Topics($this->name, $this->options);
    }
}
