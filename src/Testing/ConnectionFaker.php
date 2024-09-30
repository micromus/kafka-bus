<?php

namespace Micromus\KafkaBus\Testing;

use Micromus\KafkaBus\Consumers\Configuration as ConsumerConfiguration;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessageConverter;
use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Consumers\Consumer;
use Micromus\KafkaBus\Contracts\Producers\Producer;
use Micromus\KafkaBus\Contracts\TopicNameResolver;
use Micromus\KafkaBus\Producers\Configuration as ProducerConfiguration;
use RdKafka\Message as KafkaMessage;

class ConnectionFaker implements Connection
{
    public array $publishedMessages = [];

    public array $committedMessages = [];

    protected array $consumeMessages = [];

    public function __construct(
        protected TopicNameResolver $topicNameResolver,
    ) {}

    public function addMessage(string $topicKey, KafkaMessage $message): void
    {
        $message->topic_name = $this->topicNameResolver->resolve($topicKey);
        $message->err = RD_KAFKA_RESP_ERR_NO_ERROR;

        $this->consumeMessages[] = $message;
    }

    public function createProducer(string $topicName, ProducerConfiguration $configuration): Producer
    {
        return new ProducerFaker($this, $topicName);
    }

    public function createConsumer(array $topicNames, ConsumerConfiguration $configuration): Consumer
    {
        return new ConsumerFaker($this, new ConsumerMessageConverter, $this->consumeMessages);
    }
}
