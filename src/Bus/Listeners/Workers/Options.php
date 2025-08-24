<?php

namespace Micromus\KafkaBus\Bus\Listeners\Workers;

use Micromus\KafkaBus\Interfaces\Consumers\Pipelines\Messages\MessagePipelineMiddlewareInterface;

readonly class Options
{
    /**
     * @param array<string, int|bool|string|null> $additionalOptions
     * @param list<MessagePipelineMiddlewareInterface> $middlewares
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
