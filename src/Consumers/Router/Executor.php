<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;

class Executor
{
    public function __construct(
        protected mixed $handler,
        protected MessageFactoryInterface $factory
    ) {
    }

    public function execute(ConsumerMessageInterface $message): void
    {
        $this->handler
            ->execute($this->map($message));
    }

    private function map(ConsumerMessageInterface $message): mixed
    {
        return $this->factory
            ->fromKafka($message);
    }
}
