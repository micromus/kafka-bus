<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Consumers\Pipelines\MessagePipelineHandler;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineMiddlewareInterface;

final readonly class RouteInfo
{
    /**
     * @param string $topicKey
     * @param callable $handler
     * @param list<PipelineMiddlewareInterface<MessagePipelineHandler>> $middleware
     */
    public function __construct(
        public string $topicKey,
        public mixed $handler,
        public array $middleware = []
    ) {
    }
}
