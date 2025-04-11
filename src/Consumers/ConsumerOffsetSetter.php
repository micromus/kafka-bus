<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Consumers;

use Micromus\KafkaBus\Connections\Offsets\Offset;
use Micromus\KafkaBus\Exceptions\CannotSetOffsetForPartitionsException;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionOffsetInterface;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionRegistryInterface;
use Micromus\KafkaBus\Topics\Partition;
use Micromus\KafkaBus\Topics\TopicRegistry;

final class ConsumerOffsetSetter
{
    public function __construct(
        protected TopicRegistry $topicRegistry,
        protected ConnectionRegistryInterface $connectionRegistry
    ) {
    }

    /**
     * @param string $connectionName
     * @param string $topicKey
     * @param int $partition
     * @param Offset|int $offset
     * @return int[]
     *
     * @throws CannotSetOffsetForPartitionsException
     */
    public function set(
        string $connectionName,
        string $topicKey,
        int $partition = RD_KAFKA_PARTITION_UA,
        Offset|int $offset = Offset::Latest,
    ): array {
        $topic = $this->topicRegistry->get($topicKey);
        $partition = new Partition($topic, $partition);

        $connection = $this->connectionRegistry->connection($connectionName);

        if (!$connection instanceof ConnectionOffsetInterface) {
            throw new CannotSetOffsetForPartitionsException(
                "Connection doesn't support offset setting \"{$connectionName}\""
            );
        }

        return $connection->setOffset($partition, $offset);
    }
}
