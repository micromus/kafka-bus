<?php

namespace Micromus\KafkaBus\Interfaces\Connections;

use Micromus\KafkaBus\Consumers\Configuration as ConsumerConfiguration;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;
use Micromus\KafkaBus\Producers\Configuration as ProducerConfiguration;

interface ConnectionInterface
{
    public function createProducer(string $topicName, ProducerConfiguration $configuration): ProducerInterface;

    public function createConsumer(array $topicNames, ConsumerConfiguration $configuration): ConsumerInterface;
}
