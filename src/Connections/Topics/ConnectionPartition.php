<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Connections\Topics;

final readonly class ConnectionPartition
{
    public function __construct(
        public int $id,
        public int $offset
    ) {
    }
}
