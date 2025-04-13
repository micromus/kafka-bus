<?php

namespace Micromus\KafkaBus\Producers;

readonly class ProducerConfig
{
    public function __construct(
        public array $additionalOptions = [],
        public int $flushTimeout = 5000,
        public int $flushRetries = 10
    ) {
    }
}
