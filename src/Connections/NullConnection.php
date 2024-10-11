<?php

namespace Micromus\KafkaBus\Connections;

use Micromus\KafkaBus\Consumers\Configuration as ConsumerConfiguration;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;
use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Producers\Configuration as ProducerConfiguration;
use Micromus\KafkaBus\Producers\NullProducer;

class NullConnection implements ConnectionInterface
{
    public function createProducer(string $topicName, ProducerConfiguration $configuration): ProducerInterface
    {
        return new NullProducer();
    }

    /**
     * @throws ConsumerException
     */
    public function createConsumer(array $topicNames, ConsumerConfiguration $configuration): ConsumerInterface
    {
        throw new ConsumerException('Cannot create consumer for null connection');
    }
}
