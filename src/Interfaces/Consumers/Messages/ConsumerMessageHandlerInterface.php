<?php

namespace Micromus\KafkaBus\Interfaces\Consumers\Messages;

use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerNotHandledException;
use Micromus\KafkaBus\Topics\Topic;

interface ConsumerMessageHandlerInterface
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
