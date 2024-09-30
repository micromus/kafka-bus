<?php

namespace Micromus\KafkaBus\Contracts\Bus;

use Micromus\KafkaBus\Contracts\Messages\Message;
use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\MessagesCompletedConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\TimeoutConsumerException;
use Micromus\KafkaBus\Exceptions\Producers\RouteProducerException;

interface Thread
{
    /**
     * @throws RouteProducerException
     */
    public function publish(Message $message): void;

    /**
     * @param  Message[]  $messages
     *
     * @throws RouteProducerException
     */
    public function publishMany(array $messages): void;

    /**
     * @throws ConsumerException
     * @throws MessagesCompletedConsumerException
     * @throws TimeoutConsumerException
     */
    public function listen(string $listenerName): void;
}
