<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Connections\Offsets;

use Micromus\KafkaBus\Exceptions\CannotSetOffsetForPartitionsException;
use Micromus\KafkaBus\Topics\Partition;
use Micromus\KafkaBus\Topics\Topic;
use RdKafka\Exception;
use RdKafka\KafkaConsumer;
use RdKafka\TopicPartition;

final class OffsetConnectionSetter
{
    protected OffsetExtractor $offsetExtractor;

    public function __construct(
        protected KafkaConsumer $kafkaConsumer,
    ) {
        $this->offsetExtractor = new OffsetExtractor($this->kafkaConsumer);
    }

    /**
     * @param Partition $partition
     * @param Offset|int $offset
     * @return int[]
     *
     * @throws CannotSetOffsetForPartitionsException
     */
    public function set(Partition $partition, Offset|int $offset): array
    {
        try {
            if ($partition->partition !== RD_KAFKA_PARTITION_UA) {
                return $this->commitOnePartition($partition, $offset);
            }

            return $this->commitManyPartitions($partition, $offset);
        }
        catch (Exception $exception) {
            throw new CannotSetOffsetForPartitionsException(
                "Cannot set offset for partition: {$partition->partition}",
                $exception
            );
        }
    }

    private function makeTopicPartition(Partition $partition, Offset|int $offset): TopicPartition
    {
        if ($offset instanceof Offset) {
            $offset = $this->offsetExtractor
                ->offset($partition, $offset == Offset::Latest);
        }

        return new TopicPartition(
            $partition->topic->name,
            $partition->partition,
            $offset
        );
    }

    /**
     * @param Partition $partition
     * @param Offset|int $offset
     * @return int[]
     *
     * @throws Exception
     */
    private function commitOnePartition(Partition $partition, Offset|int $offset): array
    {
        $topicPartition = $this->makeTopicPartition($partition, $offset);

        $this->kafkaConsumer
            ->commit([$topicPartition]);

        return [$topicPartition->getOffset()];
    }

    /**
     * @param Partition $partition
     * @param Offset|int $offset
     * @return int[]
     *
     * @throws Exception
     */
    private function commitManyPartitions(Partition $partition, Offset|int $offset): array
    {
        $topicPartitions = array_map(function (TopicPartition $topicPartition) use ($partition, $offset) {
            return $this->makeTopicPartition(
                new Partition(
                    $partition->topic,
                    $topicPartition->getPartition()
                ),
                $offset
            );
        }, $this->getTopicPartitions($partition->topic));

        return array_map(
            fn (TopicPartition $topicPartition) => $topicPartition->getOffset(),
            $topicPartitions
        );
    }

    /**
     * @param Topic $topic
     * @return TopicPartition[]
     *
     * @throws Exception
     */
    private function getTopicPartitions(Topic $topic): array
    {
        $metadata = $this->kafkaConsumer
            ->getMetadata(true, null, 1000);

        foreach ($metadata->getTopics() as $topicMeta) {
            if ($topicMeta->getTopic() != $topic->name) {
                continue;
            }

            $requestPartitions = [];

            foreach ($topicMeta->getPartitions() as $partitionMeta) {
                $requestPartitions[] = new TopicPartition($topic->name, $partitionMeta->getId());
                $partitionMeta->getId();
            }

            return $this->kafkaConsumer->getCommittedOffsets($requestPartitions, 1000);
        }

        return [];
    }
}
