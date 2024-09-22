<?php

namespace Micromus\KafkaBus\Testing;

use Micromus\KafkaBus\Contracts\Messages\Message;

class ProducerMessageFaker implements Message
{
    public function __construct(
        protected string $message
    ) {
    }

    public function toPayload(): string
    {
        return $this->message;
    }
}
