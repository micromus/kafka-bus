<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Consumers\Pipelines\MessagePipelineHandler;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;
use Micromus\KafkaBus\Pipelines\PipelineBuilder;

final readonly class RouteExecutor
{
    /**
     * @param callable $handler
     * @param MessageFactoryInterface $factory
     * @param list<MessagePipelineMiddleware> $middleware
     */
    public function __construct(
        protected mixed $handler,
        protected MessageFactoryInterface $factory,
        protected array $middleware = []
    ) {
    }

    public function execute(ConsumerMessageInterface $message): void
    {
        $pipelineHandler = new MessagePipelineHandler($this->handler, $this->map($message));

        $pipeline = PipelineBuilder::for($pipelineHandler)
            ->middleware($this->middleware)
            ->create();

        $pipeline->start();
    }

    private function map(ConsumerMessageInterface $message): mixed
    {
        return $this->factory
            ->fromKafka($message);
    }
}
