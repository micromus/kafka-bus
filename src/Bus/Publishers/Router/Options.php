<?php

namespace Micromus\KafkaBus\Bus\Publishers\Router;

use Micromus\KafkaBus\Interfaces\Producers\Pipelines\ProducerPipelineMiddlewareInterface;

readonly class Options
{
    /**
     * @param array<string, int|bool|string|null> $additionalOptions
     * @param list<ProducerPipelineMiddlewareInterface> $middlewares
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
