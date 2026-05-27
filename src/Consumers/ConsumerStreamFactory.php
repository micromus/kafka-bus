<?php

namespace Micromus\KafkaBus\Consumers;

use Micromus\KafkaBus\Bus\Listeners\Workers\Options;
use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;
use Micromus\KafkaBus\Consumers\Handlers\MessageHandlerFactory;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerStreamFactoryInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerStreamInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Handlers\MessageHandlerFactoryInterface;

class ConsumerStreamFactory implements ConsumerStreamFactoryInterface
{
    public function __construct(
        protected MessageHandlerFactoryInterface $messageHandlerFactory = new MessageHandlerFactory(),
    ) {
    }

    public function create(ConnectionInterface $connection, Worker $worker): ConsumerStreamInterface
    {
        $configuration = $this->makeConsumerConfig($worker->options);

        $consumerMessageHandler = $this->messageHandlerFactory
            ->create($worker);

        return new ConsumerStream(
            $connection->createConsumer($consumerMessageHandler->topics(), $configuration),
            $consumerMessageHandler,
            $worker
        );
    }

    private function makeConsumerConfig(Options $options): ConsumerConfig
    {
        return new ConsumerConfig(
            additionalOptions: $options->additionalOptions,
            autoCommit: $options->autoCommit,
            consumerTimeout: $options->consumerTimeout,
        );
    }
}
