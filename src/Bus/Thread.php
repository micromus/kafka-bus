<?php

namespace Micromus\KafkaBus\Bus;

use Micromus\KafkaBus\Contracts\Bus\Thread as ThreadContract;
use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Contracts\Messages\Message;
use Micromus\KafkaBus\Producers\Router\ProducerRouter;

class Thread implements ThreadContract
{
    public function __construct(
        protected Connection $connection,
        protected ProducerRouter $producerRouter,
        protected ConsumerStreamFactory $consumerStreamFactory
    ) {}

    public function publish(Message $message): void
    {
        $this->publishMany([$message]);
    }

    public function publishMany(array $messages): void
    {
        $this->producerRouter
            ->publish($messages);
    }

    public function listen(?string $listenerName = null): void
    {
        $this->consumerStreamFactory
            ->create($this->connection, $listenerName)
            ->listen();
    }
}
