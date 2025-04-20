<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Connections\Topics;

final readonly class ConnectionTopic
{
    public function __construct(
        public string $topicName,
        public string $connectionName,
        /** @var ConnectionPartition[] $partitions */
        public array $partitions = [],
    ) {
    }
}
