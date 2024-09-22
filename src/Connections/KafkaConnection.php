<?php

namespace Micromus\KafkaBus\Connections;

use Micromus\KafkaBus\Consumers\ConsumerConfiguration;
use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Consumers\Consumer;
use Micromus\KafkaBus\Contracts\Producers\Producer;
use Micromus\KafkaBus\Producers\ProducerConfiguration;

class KafkaConnection implements Connection
{
    public function __construct(
        protected array $options
    ) {}

    public function createProducer(string $topic, ProducerConfiguration $configuration): Producer
    {
        // TODO: Implement createProducer() method.
    }

    public function createConsumer(string $topic, ConsumerConfiguration $configuration): Consumer
    {
        // TODO: Implement createConsumer() method.
    }
}
