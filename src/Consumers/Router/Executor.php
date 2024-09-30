<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Micromus\KafkaBus\Contracts\Messages\MessageFactory;

class Executor
{
    public function __construct(
        protected mixed $handler,
        protected MessageFactory $factory
    ) {}

    public function execute(ConsumerMessage $message): void
    {
        $this->handler
            ->execute($this->map($message));
    }

    private function map(ConsumerMessage $message): mixed
    {
        return $this->factory
            ->fromKafka($message);
    }
}
