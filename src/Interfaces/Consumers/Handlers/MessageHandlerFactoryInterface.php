<?php

namespace Micromus\KafkaBus\Interfaces\Consumers\Handlers;

use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;

interface MessageHandlerFactoryInterface
{
    public function create(Worker $worker): MessageHandlerInterface;
}
