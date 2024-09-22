<?php

namespace Micromus\KafkaBus\Testing;

use Micromus\KafkaBus\Consumers\ConsumerConfiguration;
use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Consumers\Consumer;
use Micromus\KafkaBus\Contracts\Producers\Producer;
use Micromus\KafkaBus\Producers\ProducerConfiguration;

class ConnectionFaker implements Connection
{
    public array $publishedMessages = [];

    protected array $consumeMessages = [];

    public function createProducer(string $topic, ProducerConfiguration $configuration): Producer
    {
        return new ProducerFaker($this, $topic);
    }

    public function createConsumer(string $topic, ConsumerConfiguration $configuration): Consumer
    {
        return new ConsumerFaker($this->consumeMessages);
    }
}
