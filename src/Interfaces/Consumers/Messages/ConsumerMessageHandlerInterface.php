<?php

namespace Micromus\KafkaBus\Interfaces\Consumers\Messages;

use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerNotHandledException;

interface ConsumerMessageHandlerInterface
{
    public function topics(): array;

    /**
     * @param ConsumerMessageInterface $message
     * @return void
     *
     * @throws MessageConsumerNotHandledException
     */
    public function handle(ConsumerMessageInterface $message): void;
}
