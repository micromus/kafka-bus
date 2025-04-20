<?php

namespace Micromus\KafkaBus\Interfaces\Connections\Topics;

use Micromus\KafkaBus\Connections\Topics\ConnectionTopic;
use Micromus\KafkaBus\Consumers\ConsumerConfig;
use Micromus\KafkaBus\Exceptions\TopicNotFoundException;

interface ConnectionTopicsInterface
{
    public function consume(ConsumerConfig $config): ConnectionConsumerTopicsInterface;

    /**
     * @return ConnectionTopic[]
     */
    public function list(): array;

    /**
     * @param string $topicName
     * @return ConnectionTopic
     *
     * @throws TopicNotFoundException
     */
    public function get(string $topicName): ConnectionTopic;
}
