<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Topics;

final readonly class Partition
{
    public function __construct(
        public Topic $topic,
        public int $partition = RD_KAFKA_PARTITION_UA,
    ) {
    }
}
