<?php

namespace Micromus\KafkaBus\Consumers\Handlers;

use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouter;
use Micromus\KafkaBus\Interfaces\Consumers\Handlers\MessageHandlerFactoryInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Handlers\MessageHandlerInterface;

final class MessageHandlerFactory implements MessageHandlerFactoryInterface
{
    public function create(Worker $worker): MessageHandlerInterface
    {
        return new MessageHandler(new ConsumerRouter($worker->routes));
    }
}
