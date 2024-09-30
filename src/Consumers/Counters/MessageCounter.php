<?php

namespace Micromus\KafkaBus\Consumers\Counters;

class MessageCounter
{
    private int $messageCount = 0;

    public function __construct(
        protected int $maxMessages = -1,
    ) {
    }

    public function isCompleted(): bool
    {
        return $this->maxMessages > 0
            && $this->messageCount <= $this->maxMessages;
    }

    public function increment(): void
    {
        if ($this->maxMessages > 0) {
            $this->messageCount++;
        }
    }
}
