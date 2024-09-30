<?php

namespace Micromus\KafkaBus\Consumers\Counters;

class Timer
{
    private float $startTime;

    public function __construct(
        protected int $maxTimeInSeconds = -1
    ) {}

    public function isTimeout(): bool
    {
        return $this->maxTimeInSeconds > 0
            && microtime(true) - $this->startTime >= $this->maxTimeInSeconds;
    }

    public function start(): void
    {
        $this->startTime = microtime(true);
    }
}
