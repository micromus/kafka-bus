<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Bus\Listeners\Partitions;

use Micromus\KafkaBus\Topics\Topic;

final readonly class CommitOffset
{
    public function __construct(
        public Topic $topic,
        public int $partition,
        public Offset|int $offset,
    ) {
    }
}
