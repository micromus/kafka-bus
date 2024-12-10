<?php

namespace Micromus\KafkaBus\Testing\Messages;

use Micromus\KafkaBus\Interfaces\Producers\Messages\HasHeaders;
use Micromus\KafkaBus\Interfaces\Producers\Messages\HasPartition;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;

final class ProducerMessageFaker implements HasHeaders, HasPartition, ProducerMessageInterface
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
