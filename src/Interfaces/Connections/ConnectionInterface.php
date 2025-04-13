<?php

namespace Micromus\KafkaBus\Interfaces\Connections;

use Micromus\KafkaBus\Consumers\ConsumerConfig;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;
use Micromus\KafkaBus\Producers\ProducerConfig;
use Micromus\KafkaBus\Topics\Topic;

interface ConnectionInterface
{
    public function createProducer(Topic $topic, ProducerConfig $config): ProducerInterface;

    /**
     * @param Topic[] $topics
     * @param ConsumerConfig $config
     * @return ConsumerInterface
     */
    public function createConsumer(array $topics, ConsumerConfig $config): ConsumerInterface;
}
