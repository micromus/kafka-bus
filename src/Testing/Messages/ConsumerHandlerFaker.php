<?php

namespace Micromus\KafkaBus\Testing\Messages;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;

final class ConsumerHandlerFaker
{
    public function __invoke(ConsumerMessageInterface $message): void
    {
        echo $message->payload() . PHP_EOL;
    }
}
