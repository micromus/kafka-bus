<?php

namespace Micromus\KafkaBus\Connections;

use Micromus\KafkaBus\Consumers\ConsumerConfiguration;
use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Consumers\Consumer;
use Micromus\KafkaBus\Contracts\Producers\Producer;
use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Producers\NullProducer;
use Micromus\KafkaBus\Producers\ProducerConfiguration;

class NullConnection implements Connection
{
    public function createProducer(string $topic, ProducerConfiguration $configuration): Producer
    {
        return new NullProducer();
    }

    /**
     * @throws ConsumerException
     */
    public function createConsumer(string $topic, ConsumerConfiguration $configuration): Consumer
    {
        throw new ConsumerException('Cannot create consumer for null connection');
    }
}