<?php

namespace Micromus\KafkaBus\Contracts\Consumers;

use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\MessagesCompletedConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\TimeoutConsumerException;

interface ConsumerStream
{
    /**
     * @throws MessagesCompletedConsumerException
     * @throws TimeoutConsumerException
     * @throws ConsumerException
     */
    public function listen(): void;
}
