<?php

namespace Micromus\KafkaBus\Interfaces\Consumers;

use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\MessagesCompletedConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\TimeoutConsumerException;

interface ConsumerStreamInterface
{
    /**
     * @throws MessagesCompletedConsumerException
     * @throws TimeoutConsumerException
     * @throws ConsumerException
     */
    public function listen(): void;
}
