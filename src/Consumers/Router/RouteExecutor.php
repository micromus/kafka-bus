<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Consumers\Pipelines\Messages\MessagePipelineHandler;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Pipelines\Messages\MessagePipelineMiddlewareInterface;
use Micromus\KafkaBus\Pipelines\Pipeline;
use Micromus\KafkaBus\Pipelines\PipelineBuilder;

final readonly class RouteExecutor
{
    /**
     * @param callable $handler
     * @param MessageFactoryInterface $factory
     * @param list<MessagePipelineMiddlewareInterface> $middlewares
     */
    public function __construct(
        protected mixed $handler,
        protected MessageFactoryInterface $factory,
        protected array $middlewares = []
    ) {
    }

    public function execute(ConsumerMessageInterface $message): void
    {
        $pipelineHandler = new MessagePipelineHandler($this->handler, $this->map($message));

        $pipeline = PipelineBuilder::for($pipelineHandler)
            ->middleware($this->middlewares)
            ->create();

        $pipeline->start();
    }

    private function map(ConsumerMessageInterface $message): mixed
    {
        return $this->factory
            ->fromKafka($message);
    }
}
