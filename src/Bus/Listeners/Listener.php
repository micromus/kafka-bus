<?php

namespace Micromus\KafkaBus\Bus\Listeners;

use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\MessagesCompletedConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\TimeoutConsumerException;

class Listener
{
    public function __construct(
        protected Connection $connection,
        protected ConsumerStreamFactory $consumerStreamFactory,
        protected Options $options
    ) {}

    /**
     * @throws ConsumerException
     * @throws MessagesCompletedConsumerException
     * @throws TimeoutConsumerException
     */
    public function listen(): void
    {
        $this->consumerStreamFactory
            ->create($this->connection, $this->options)
            ->listen();
    }
}
