<?php

namespace Micromus\KafkaBus\Connections;

use Micromus\KafkaBus\Consumers\ConsumerConfig;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;
use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Producers\NullProducer;
use Micromus\KafkaBus\Producers\ProducerConfig;
use Micromus\KafkaBus\Topics\Topic;

final class NullConnection implements ConnectionInterface
{
    public function __construct(
        protected string $name,
        protected array $options
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getConfig(): KafkaConnectionConfig
    {
        return new KafkaConnectionConfig($this->options);
    }

    public function createProducer(Topic $topic, ProducerConfig $config): ProducerInterface
    {
        return new NullProducer();
    }

    /**
     * @throws ConsumerException
     */
    public function createConsumer(array $topics, ConsumerConfig $config): ConsumerInterface
    {
        throw new ConsumerException('Cannot create consumer for null connection');
    }
}
