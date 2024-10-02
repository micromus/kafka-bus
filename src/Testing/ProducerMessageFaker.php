<?php

namespace Micromus\KafkaBus\Testing;

use Micromus\KafkaBus\Contracts\Messages\HasHeaders;
use Micromus\KafkaBus\Contracts\Messages\HasPartition;
use Micromus\KafkaBus\Contracts\Messages\Message;

class ProducerMessageFaker implements HasHeaders, HasPartition, Message
{
    public function __construct(
        protected string $message,
        protected array $headers = [],
        protected int $partition = -1,
    ) {}

    public function toPayload(): string
    {
        return $this->message;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getPartition(int $partitions): int
    {
        return $this->partition;
    }
}
