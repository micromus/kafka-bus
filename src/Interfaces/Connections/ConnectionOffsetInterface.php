<?php

namespace Micromus\KafkaBus\Interfaces\Connections;

use Micromus\KafkaBus\Connections\Offsets\Offset;
use Micromus\KafkaBus\Consumers\ConsumerConfig;
use Micromus\KafkaBus\Exceptions\CannotSetOffsetForPartitionsException;
use Micromus\KafkaBus\Topics\Partition;

interface ConnectionOffsetInterface
{
    /**
     * @param Partition $partition
     * @param Offset|int $offset
     * @param ConsumerConfig $config
     * @return int[]
     *
     * @throws CannotSetOffsetForPartitionsException
     */
    public function setOffset(Partition $partition, Offset|int $offset, ConsumerConfig $config): array;
}
