<?php

namespace Micromus\KafkaBus\Interfaces\Consumers;

use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\MessagesCompletedConsumerException;

interface ConsumerStreamInterface
{
    /**
     * @throws MessagesCompletedConsumerException
     * @throws ConsumerException
     */
    public function listen(): void;

    public function forceStop(): void;
}
