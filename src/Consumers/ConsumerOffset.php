<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Consumers;

use Micromus\KafkaBus\Connections\Offsets\Offset;

final readonly class ConsumerOffset
{
    public function __construct(
        public string $workerName,
        public string $topicKey,
        public int $partition = RD_KAFKA_PARTITION_UA,
        public Offset|int $offset = Offset::Latest,
    ) {
    }
}
