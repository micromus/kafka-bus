<?php

namespace Micromus\KafkaBus\Contracts\Connections;

use Micromus\KafkaBus\Consumers\Configuration as ConsumerConfiguration;
use Micromus\KafkaBus\Contracts\Consumers\Consumer;
use Micromus\KafkaBus\Contracts\Producers\Producer;
use Micromus\KafkaBus\Producers\Configuration as ProducerConfiguration;

interface Connection
{
    public function createProducer(string $topicName, ProducerConfiguration $configuration): Producer;

    public function createConsumer(array $topicNames, ConsumerConfiguration $configuration): Consumer;
}
