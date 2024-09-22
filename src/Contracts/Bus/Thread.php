<?php

namespace Micromus\KafkaBus\Contracts\Bus;

use Micromus\KafkaBus\Contracts\Messages\Message;
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

    public function listen(?string $listenerName = null): void;
}
