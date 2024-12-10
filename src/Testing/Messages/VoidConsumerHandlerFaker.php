<?php

namespace Micromus\KafkaBus\Testing\Messages;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;

class VoidConsumerHandlerFaker
{
    public function execute(ConsumerMessageInterface $message): void
    {

    }
}
