<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Consumers\Messages\NativeMessageFactory;
use Micromus\KafkaBus\Consumers\Pipelines\MessagePipelineHandler;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineMiddlewareInterface;
use Micromus\KafkaBus\Topics\Topic;

final readonly class Route
{
    /**
     * @param Topic $topic
     * @param callable $handler
     * @param MessageFactoryInterface $messageFactory
     * @param list<PipelineMiddlewareInterface<MessagePipelineHandler>> $middleware
     */
    public function __construct(
        public Topic $topic,
        public mixed $handler,
        public MessageFactoryInterface $messageFactory = new NativeMessageFactory(),
        public array $middleware = []
    ) {
    }
}
