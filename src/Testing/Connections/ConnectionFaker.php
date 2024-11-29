<?php

namespace Micromus\KafkaBus\Testing\Connections;

use Micromus\KafkaBus\Consumers\Configuration as ConsumerConfiguration;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessageConverter;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;
use Micromus\KafkaBus\Producers\Configuration as ProducerConfiguration;
use Micromus\KafkaBus\Testing\ConsumerFaker;
use Micromus\KafkaBus\Testing\ProducerFaker;
use Micromus\KafkaBus\Topics\TopicRegistry;
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

    public function createProducer(string $topicName, ProducerConfiguration $configuration): ProducerInterface
    {
        return new ProducerFaker($this, $topicName);
    }

    public function createConsumer(array $topicNames, ConsumerConfiguration $configuration): ConsumerInterface
    {
        return new ConsumerFaker($this, new ConsumerMessageConverter(), $this->consumeMessages);
    }
}
