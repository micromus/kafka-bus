<?php

namespace Micromus\KafkaBus\Consumers\Messages;

use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouterFactory;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageHandlerFactoryInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageHandlerInterface;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineFactoryInterface;

class ConsumerMessageHandlerFactory implements ConsumerMessageHandlerFactoryInterface
{
    public function __construct(
        protected PipelineFactoryInterface $pipelineFactory,
        protected ConsumerRouterFactory $consumerRouterFactory,
    ) {
    }

    public function create(Worker $worker): ConsumerMessageHandlerInterface
    {
        return new ConsumerMessageHandler(
            $worker->name,
            $this->consumerRouterFactory->create($worker->routes),
            $this->pipelineFactory->create($worker->options->middlewares)
        );
    }
}
