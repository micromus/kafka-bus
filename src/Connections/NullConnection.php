<?php

namespace Micromus\KafkaBus\Connections;

use Micromus\KafkaBus\Consumers\Configuration as ConsumerConfiguration;
use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Consumers\Consumer;
use Micromus\KafkaBus\Contracts\Producers\Producer;
use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Producers\Configuration as ProducerConfiguration;
use Micromus\KafkaBus\Producers\NullProducer;

class NullConnection implements Connection
{
    public function createProducer(string $topicName, ProducerConfiguration $configuration): Producer
    {
        return new NullProducer;
    }

    /**
     * @throws ConsumerException
     */
    public function createConsumer(array $topicNames, ConsumerConfiguration $configuration): Consumer
    {
        throw new ConsumerException('Cannot create consumer for null connection');
    }
}
