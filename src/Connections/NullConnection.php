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

class NullConnection implements ConnectionInterface
{
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
