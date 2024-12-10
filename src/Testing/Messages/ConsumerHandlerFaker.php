<?php

namespace Micromus\KafkaBus\Testing\Messages;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;

class ConsumerHandlerFaker
{
    public function execute(ConsumerMessageInterface $message): void
    {
        echo $message->payload() . PHP_EOL;
    }
}
