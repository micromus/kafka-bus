<?php

namespace Micromus\KafkaBus\Testing\Connections;

use Micromus\KafkaBus\Consumers\ConsumerConfig;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessageConverter;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;
use Micromus\KafkaBus\Producers\ProducerConfig;
use Micromus\KafkaBus\Testing\ConsumerFaker;
use Micromus\KafkaBus\Testing\ProducerFaker;
use Micromus\KafkaBus\Topics\Topic;
use RdKafka\Message as KafkaMessage;

class ConnectionFaker implements ConnectionInterface
{
    public array $publishedMessages = [];

    public array $committedMessages = [];

    protected array $consumeMessages = [];

    public function addMessage(KafkaMessage $message): void
    {
        $this->consumeMessages[] = $message;
    }

    public function createProducer(Topic $topic, ProducerConfig $config): ProducerInterface
    {
        return new ProducerFaker($this, $topic->name);
    }

    public function createConsumer(array $topics, ConsumerConfig $config): ConsumerInterface
    {
        return new ConsumerFaker($this, new ConsumerMessageConverter(), $this->consumeMessages);
    }
}
