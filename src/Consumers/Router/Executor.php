<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineInterface;

class Executor
{
    public function __construct(
        protected mixed $handler,
        protected PipelineInterface $pipeline,
        protected MessageFactoryInterface $factory
    ) {
    }

    public function execute(ConsumerMessageInterface $message): void
    {
        $this->pipeline
            ->then($this->map($message), function ($message) {
                return $this->handler
                    ->execute($message);
            });
    }

    private function map(ConsumerMessageInterface $message): mixed
    {
        return $this->factory
            ->fromKafka($message);
    }
}
