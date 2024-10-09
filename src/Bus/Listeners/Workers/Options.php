<?php

namespace Micromus\KafkaBus\Bus\Listeners\Workers;

readonly class Options
{
    public function __construct(
        public array $additionalOptions = [],
        public array $middlewares = [],
        public bool $autoCommit = true,
        public int $consumerTimeout = 2000,
    ) {}
}
