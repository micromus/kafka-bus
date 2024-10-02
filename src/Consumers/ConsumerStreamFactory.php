<?php

namespace Micromus\KafkaBus\Consumers;

use Micromus\KafkaBus\Bus\Listeners\Groups\Group;
use Micromus\KafkaBus\Bus\Listeners\Groups\Options;
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

    public function create(Connection $connection, Group $group): ConsumerStreamContract
    {
        $configuration = $this->makeConsumerConfiguration($group->options);
        $router = $this->consumerRouterFactory->create($group->routes);

        return new ConsumerStream(
            $connection->createConsumer($router->topics(), $configuration),
            $router,
            $this->messagePipelineFactory->create($group->options->middlewares),
            new MessageCounter($group->maxMessages),
            new Timer($group->maxTime)
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
