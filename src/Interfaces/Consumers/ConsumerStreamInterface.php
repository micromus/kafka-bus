<?php

namespace Micromus\KafkaBus\Interfaces\Consumers;

use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerNotHandledException;

interface ConsumerStreamInterface
{
    /**
     * @throws MessageConsumerException
     * @throws MessageConsumerNotHandledException
     * @throws ConsumerException
     */
    public function listen(): void;

    public function forceStop(): void;
}
