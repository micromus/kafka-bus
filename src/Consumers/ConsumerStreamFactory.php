<?php

namespace Micromus\KafkaBus\Consumers;

use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;
use Micromus\KafkaBus\Bus\Listeners\Workers\Options;
use Micromus\KafkaBus\Consumers\Counters\MessageCounter;
use Micromus\KafkaBus\Consumers\Counters\Timer;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouterFactory;
use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Consumers\ConsumerStream as ConsumerStreamContract;
use Micromus\KafkaBus\Contracts\Consumers\ConsumerStreamFactory as ConsumerStreamFactoryContract;
use Micromus\KafkaBus\Contracts\Messages\MessagePipelineFactory;

class ConsumerStreamFactory implements ConsumerStreamFactoryContract
{
    public function __construct(
        protected MessagePipelineFactory $messagePipelineFactory,
        protected ConsumerRouterFactory $consumerRouterFactory,
    ) {}

    public function create(Connection $connection, Worker $worker): ConsumerStreamContract
    {
        $configuration = $this->makeConsumerConfiguration($worker->options);
        $router = $this->consumerRouterFactory->create($worker->routes);

        return new ConsumerStream(
            $connection->createConsumer($router->topics(), $configuration),
            $router,
            $this->messagePipelineFactory->create($worker->options->middlewares),
            new MessageCounter($worker->maxMessages),
            new Timer($worker->maxTime)
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
