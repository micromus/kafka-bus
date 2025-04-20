<?php

namespace Micromus\KafkaBus\Interfaces\Connections\Topics;

use Micromus\KafkaBus\Connections\Topics\Consumers\ConsumerPartition;
use Micromus\KafkaBus\Connections\Topics\Consumers\PartitionOffset;
use Micromus\KafkaBus\Exceptions\TopicNotFoundException;

interface ConnectionConsumerTopicsInterface
{
    /**
     * @param string $topicName
     * @return ConsumerPartition[]
     *
     * @throws TopicNotFoundException
     */
    public function getConsumerPartitions(string $topicName): array;

    /**
     * @param PartitionOffset[] $offsets
     * @return void
     */
    public function setOffset(array $offsets): void;
}
