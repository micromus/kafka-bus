<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Connections\Offsets;

use Micromus\KafkaBus\Topics\Partition;
use RdKafka\KafkaConsumer;

final class OffsetExtractor
{
    public function __construct(
        protected KafkaConsumer $kafkaConsumer
    ) {
    }

    public function offset(Partition $partition, bool $latest = true): int
    {
        $this->kafkaConsumer
            ->queryWatermarkOffsets(
                $partition->topic->name,
                $partition->partition,
                $earlyOffset,
                $latestOffset,
                1000
            );

        return $latest ? $latestOffset : $earlyOffset;
    }
}
