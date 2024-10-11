<?php

namespace Micromus\KafkaBus\Interfaces\Bus;

use Micromus\KafkaBus\Interfaces\Messages\MessageInterface;
use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\MessagesCompletedConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\TimeoutConsumerException;
use Micromus\KafkaBus\Exceptions\Producers\RouteProducerException;

interface ThreadInterface
{
    /**
     * @throws RouteProducerException
     */
    public function publish(MessageInterface $message): void;

    /**
     * @param  MessageInterface[]  $messages
     *
     * @throws RouteProducerException
     */
    public function publishMany(array $messages): void;

    /**
     * @throws ConsumerException
     * @throws MessagesCompletedConsumerException
     * @throws TimeoutConsumerException
     */
    public function listen(string $listenerWorkerName): void;
}
