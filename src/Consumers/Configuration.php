<?php

namespace Micromus\KafkaBus\Consumers;

readonly class Configuration
{
    public function __construct(
        public array $additionalOptions = [],
        public bool $autoCommit = true,
        public int $consumerTimeout = 2000
    ) {}
}
