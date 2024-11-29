<?php

namespace Micromus\KafkaBus\Interfaces\Consumers\Messages;

use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;

interface ConsumerMessageHandlerFactoryInterface
{
    public function create(Worker $worker): ConsumerMessageHandlerInterface;
}
