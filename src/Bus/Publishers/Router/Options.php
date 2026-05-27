<?php

namespace Micromus\KafkaBus\Bus\Publishers\Router;

use Micromus\KafkaBus\Producers\Pipelines\ProducerPipelineMiddleware;

readonly class Options
{
    /**
     * @param array<string, int|bool|string|null> $additionalOptions
     * @param list<ProducerPipelineMiddleware> $middlewares
     * @param int $flushTimeout
     * @param int $flushRetries
     */
    public function __construct(
        public array $additionalOptions = [],
        public array $middlewares = [],
        public int $flushTimeout = 5000,
        public int $flushRetries = 10
    ) {
    }
}
