<?php

namespace Micromus\KafkaBus\Bus\Listeners;

use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerNotHandledException;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerStreamInterface;

class Listener
{
    public function __construct(
        protected ConsumerStreamInterface $consumerStream,
    ) {
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
