<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Connections\Topics;

use Micromus\KafkaBus\Connections\Config\Options;
use Micromus\KafkaBus\Connections\Kafka\KafkaProducerFactory;
use Micromus\KafkaBus\Consumers\ConsumerConfig;
use Micromus\KafkaBus\Exceptions\TopicNotFoundException;
use Micromus\KafkaBus\Interfaces\Connections\Topics\ConnectionConsumerTopicsInterface;
use Micromus\KafkaBus\Interfaces\Connections\Topics\ConnectionTopicsInterface;
use Micromus\KafkaBus\Producers\ProducerConfig;
use RdKafka\Exception;
use RdKafka\Metadata\Partition;
use RdKafka\Producer as KafkaProducer;
use RdKafka\TopicPartition;

final class Topics implements ConnectionTopicsInterface
{
    private ?KafkaProducer $producer = null;

    protected KafkaProducerFactory $producerFactory;

    public function __construct(
        protected string $connectionName,
        protected Options $config
    ) {
        $this->producerFactory = new KafkaProducerFactory($this->config);
    }

    private function producer(): KafkaProducer
    {
        if ($this->producer === null) {
            $this->producer = $this->producerFactory
                ->make(new ProducerConfig());
        }

        return $this->producer;
    }

    public function consume(ConsumerConfig $config): ConnectionConsumerTopicsInterface
    {
        return new ConsumerTopics($this->connectionName, $this->config, $config, $this);
    }

    /**
     * @return ConnectionTopic[]
     * @throws Exception
     */
    public function list(): array
    {
        $topicsMeta = $this->producer()
            ->getMetadata(true, null, 10_000);

        $topics = [];

        foreach ($topicsMeta->getTopics() as $topicMeta) {
            $topicPartitions = array_map(
                fn (Partition $partition) => new TopicPartition($topicMeta->getTopic(), $partition->getId()),
                iterator_to_array($topicMeta->getPartitions())
            );

            $partitions = array_map(
                fn (TopicPartition $topicPartition) => new ConnectionPartition($topicPartition->getPartition(), $topicPartition->getOffset()),
                $this->producer()->offsetsForTimes($topicPartitions, 10_000)
            );

            $topics[] = new ConnectionTopic($topicMeta->getTopic(), $this->connectionName, $partitions);
        }

        return $topics;
    }

    /**
     * @param string $topicName
     * @return ConnectionTopic
     *
     * @throws Exception
     * @throws TopicNotFoundException
     */
    public function get(string $topicName): ConnectionTopic
    {
        $topics = $this->list();

        foreach ($topics as $connectionTopic) {
            if ($connectionTopic->topicName == $topicName) {
                return $connectionTopic;
            }
        }

        throw new TopicNotFoundException("Topic $topicName not found");
    }
}
