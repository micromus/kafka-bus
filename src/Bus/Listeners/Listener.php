<?php

namespace Micromus\KafkaBus\Bus\Listeners;

use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerStreamFactoryInterface;
use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\MessagesCompletedConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\TimeoutConsumerException;

class Listener
{
    public function __construct(
        protected ConnectionInterface            $connection,
        protected ConsumerStreamFactoryInterface $consumerStreamFactory,
        protected Worker                         $worker
    ) {
    }

    /**
     * @throws ConsumerException
     * @throws MessagesCompletedConsumerException
     * @throws TimeoutConsumerException
     */
    public function listen(): void
    {
        $this->consumerStreamFactory
            ->create($this->connection, $this->worker)
            ->listen();
    }
}
