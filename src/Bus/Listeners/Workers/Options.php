<?php

namespace Micromus\KafkaBus\Bus\Listeners\Workers;

use Micromus\KafkaBus\Consumers\Pipelines\ConsumerPipelineHandler;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineMiddlewareInterface;

readonly class Options
{
    /**
     * @param array<string, int|bool|string|null> $additionalOptions
     * @param list<PipelineMiddlewareInterface<ConsumerPipelineHandler>> $middlewares
     * @param bool $autoCommit
     * @param int $consumerTimeout
     */
    public function __construct(
        public array $additionalOptions = [],
        public array $middlewares = [],
        public bool $autoCommit = true,
        public int $consumerTimeout = 2000,
    ) {
    }
}
