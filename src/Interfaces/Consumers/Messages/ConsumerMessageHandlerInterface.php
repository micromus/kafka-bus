<?php

namespace Micromus\KafkaBus\Interfaces\Consumers\Messages;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerNotHandledException;

interface ConsumerMessageHandlerInterface
{
    public function topics(): array;

    /**
     * @param ConsumerMessage $message
     * @return void
     *
     * @throws MessageConsumerNotHandledException
     */
    public function handle(ConsumerMessage $message): void;
}
