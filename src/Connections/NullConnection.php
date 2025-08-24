<?php

namespace Micromus\KafkaBus\Connections;

use Micromus\KafkaBus\Connections\Config\Options;
use Micromus\KafkaBus\Consumers\ConsumerConfig;
use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;
use Micromus\KafkaBus\Producers\NullProducer;
use Micromus\KafkaBus\Producers\ProducerConfig;
use Micromus\KafkaBus\Topics\Topic;

final class NullConnection implements ConnectionInterface
{
    /**
     * @param string $name
     */
    public function __construct(protected string $name)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOptions(): Options
    {
        return new Options([]);
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
