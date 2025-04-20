<?php

namespace Micromus\KafkaBus\Interfaces\Bus\Listeners;

use Micromus\KafkaBus\Bus\Listeners\Partitions\CommitOffset;
use Micromus\KafkaBus\Bus\Listeners\Partitions\CommitOffsetResult;
use Micromus\KafkaBus\Bus\Listeners\Partitions\TopicPartition;
use Micromus\KafkaBus\Exceptions\Listeners\CannotCommitOffsetException;

interface PartitionsInterface
{
    /**
     * @return iterable<TopicPartition>
     */
    public function list(): iterable;

    /**
     * @param CommitOffset $commitOffset
     * @return list<CommitOffsetResult>
     *
     * @throws CannotCommitOffsetException
     */
    public function setOffset(CommitOffset $commitOffset): array;
}
