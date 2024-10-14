<?php

namespace Micromus\KafkaBus\Testing\Messages;

use Micromus\KafkaBus\Interfaces\Messages\HasHeaders;
use Micromus\KafkaBus\Interfaces\Messages\HasPartition;
use Micromus\KafkaBus\Interfaces\Messages\MessageInterface;

class ProducerMessageFaker implements HasHeaders, HasPartition, MessageInterface
{
    public function __construct(
        protected string $message,
        protected array $headers = [],
        protected int $partition = -1,
    ) {
    }

    public function toPayload(): string
    {
        return $this->message;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getPartition(): int
    {
        return $this->partition;
    }
}
