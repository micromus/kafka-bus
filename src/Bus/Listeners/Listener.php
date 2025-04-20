<?php

namespace Micromus\KafkaBus\Bus\Listeners;

use LogicException;
use Micromus\KafkaBus\Bus\Listeners\Partitions\Partitions;
use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;
use Micromus\KafkaBus\Consumers\ConsumerConfig;
use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerNotHandledException;
use Micromus\KafkaBus\Interfaces\Bus\Listeners\ListenerInterface;
use Micromus\KafkaBus\Interfaces\Bus\Listeners\PartitionsInterface;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionHasTopicsInterface;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerStreamInterface;

class Listener implements ListenerInterface
{
    public function __construct(
        protected Worker $worker,
        protected ConnectionInterface $connection,
        protected ConsumerStreamInterface $consumerStream
    ) {
    }

    /**
     * @return PartitionsInterface
     */
    public function partitions(): PartitionsInterface
    {
        if (!$this->connection instanceof ConnectionHasTopicsInterface) {
            throw new LogicException("Connection {$this->connection->getName()} not supported topics");
        }

        $consumerConfig = new ConsumerConfig($this->worker->options->additionalOptions);
        $consumerTopics = $this->connection->topics()->consume($consumerConfig);

        return new Partitions($this->worker, $consumerTopics);
    }

    public function forceStop(): void
    {
        $this->consumerStream
            ->forceStop();
    }

    /**
     * @throws ConsumerException
     * @throws MessageConsumerNotHandledException
     * @throws MessageConsumerException
     */
    public function listen(): void
    {
        $this->consumerStream
            ->listen();
    }
}
