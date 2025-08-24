<?php

namespace Micromus\KafkaBus\Interfaces\Consumers\Handlers;

use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerNotHandledException;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\WorkerConsumerMessageInterface;
use Micromus\KafkaBus\Topics\Topic;

interface MessageHandlerInterface
{
    /**
     * @return Topic[]
     */
    public function topics(): array;

    /**
     * @param WorkerConsumerMessageInterface $message
     * @return void
     *
     * @throws MessageConsumerNotHandledException
     */
    public function handle(WorkerConsumerMessageInterface $message): void;
}
