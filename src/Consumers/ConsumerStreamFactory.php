<?php

namespace Micromus\KafkaBus\Consumers;

use InvalidArgumentException;
use Micromus\KafkaBus\Bus\Listeners\Options;
use Micromus\KafkaBus\Consumers\Counters\MessageCounter;
use Micromus\KafkaBus\Consumers\Counters\Timer;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouter;
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
    ) {
    }

    public function create(Connection $connection, Options $options): ConsumerStreamContract
    {
        $configuration = $this->makeConsumerConfiguration($options);
        $router = $this->consumerRouterFactory->create($options->routes);

        return new ConsumerStream(
            $connection->createConsumer($router->topics(), $configuration),
            $router,
            $this->messagePipelineFactory->create($options->additionalOptions),
            new MessageCounter($options->maxMessages),
            new Timer($options->maxTime)
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
