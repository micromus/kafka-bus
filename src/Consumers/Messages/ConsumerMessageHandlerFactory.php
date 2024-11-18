<?php

namespace Micromus\KafkaBus\Consumers\Messages;

use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouterFactory;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageHandlerInterface;
use Micromus\KafkaBus\Interfaces\Messages\MessagePipelineFactoryInterface;

class ConsumerMessageHandlerFactory
{
    public function __construct(
        protected MessagePipelineFactoryInterface $messagePipelineFactory,
        protected ConsumerRouterFactory $consumerRouterFactory,
    ) {
    }

    public function create(Worker $worker): ConsumerMessageHandlerInterface
    {
        return new ConsumerMessageHandler(
            $this->consumerRouterFactory->create($worker->routes),
            $this->messagePipelineFactory->create($worker->options->middlewares)
        );
    }
}
