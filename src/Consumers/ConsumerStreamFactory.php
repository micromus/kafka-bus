<?php

namespace Micromus\KafkaBus\Consumers;

use Micromus\KafkaBus\Bus\Listeners\Workers\Options;
use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerStreamInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerStreamFactoryInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageHandlerFactoryInterface;

class ConsumerStreamFactory implements ConsumerStreamFactoryInterface
{
    public function __construct(
        protected ConsumerMessageHandlerFactoryInterface $consumerMessageHandlerFactory,
    ) {
    }

    public function create(ConnectionInterface $connection, Worker $worker): ConsumerStreamInterface
    {
        $configuration = $this->makeConsumerConfiguration($worker->options);

        $consumerMessageHandler = $this->consumerMessageHandlerFactory
            ->create($worker);

        return new ConsumerStream(
            $connection->createConsumer($consumerMessageHandler->topics(), $configuration),
            $consumerMessageHandler,
            $worker->name
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
