<?php

namespace Micromus\KafkaBus\Testing\Connections;

use Micromus\KafkaBus\Connections\Config\Options;
use Micromus\KafkaBus\Consumers\ConsumerConfig;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessageConverter;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;
use Micromus\KafkaBus\Producers\Messages\ProducerMessage;
use Micromus\KafkaBus\Producers\ProducerConfig;
use Micromus\KafkaBus\Testing\ConsumerFaker;
use Micromus\KafkaBus\Testing\ProducerFaker;
use Micromus\KafkaBus\Topics\Topic;
use Micromus\KafkaBus\Topics\TopicRegistry;
use RdKafka\Message;
use RdKafka\Message as KafkaMessage;

class ConnectionFaker implements ConnectionInterface
{
    /**
     * @var array<string, list<ProducerMessage>>
     */
    public array $publishedMessages = [];

    /**
     * @var array<string, list<ConsumerMessageInterface>>
     */
    public array $committedMessages = [];

    /**
     * @var array<int, Message>
     */
    protected array $consumeMessages = [];

    private Options $options;

    private string $name;

    public function __construct(private readonly TopicRegistry $topicRegistry)
    {
        $this->name = 'faker';
        $this->options = new Options([]);
    }

    public function addMessage(KafkaMessage $message): void
    {
        $message->topic_name = $this->topicRegistry
            ->tryGetTopicName($message->topic_name);

        $this->consumeMessages[] = $message;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getOptions(): Options
    {
        return $this->options;
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
