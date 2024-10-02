<?php

namespace Micromus\KafkaBus\Bus\Publishers\Router;

readonly class Options
{
    public function __construct(
        public array $additionalOptions = [],
        public array $middlewares = [],
        public int $flushTimeout = 5000,
        public int $flushRetries = 10
    ) {}
}
