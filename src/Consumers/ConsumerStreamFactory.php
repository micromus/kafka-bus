<?php

namespace Micromus\KafkaBus\Consumers;

use Micromus\KafkaBus\Bus\Listeners\Workers\Options;
use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;
use Micromus\KafkaBus\Consumers\Counters\MessageCounter;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouterFactory;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerStreamInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerStreamFactoryInterface;
use Micromus\KafkaBus\Interfaces\Messages\MessagePipelineFactoryInterface;

class ConsumerStreamFactory implements ConsumerStreamFactoryInterface
{
    public function __construct(
        protected MessagePipelineFactoryInterface $messagePipelineFactory,
        protected ConsumerRouterFactory           $consumerRouterFactory,
    ) {
    }

    public function create(ConnectionInterface $connection, Worker $worker): ConsumerStreamInterface
    {
        $configuration = $this->makeConsumerConfiguration($worker->options);
        $router = $this->consumerRouterFactory->create($worker->routes);

        return new ConsumerStream(
            $connection->createConsumer($router->topics(), $configuration),
            $router,
            $this->messagePipelineFactory->create($worker->options->middlewares),
            new MessageCounter($worker->maxMessages)
        );
    }

    private function makeConsumerConfiguration(Options $options): Configuration
    {
        return new Configuration(
            additionalOptions: $options->additionalOptions,
            autoCommit: $options->autoCommit,
            consumerTimeout: $options->consumerTimeout,
        );
    }
}
