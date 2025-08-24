<?php

namespace Micromus\KafkaBus\Testing\Messages;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;

final class VoidConsumerHandlerFaker
{
    public function __invoke(ConsumerMessageInterface $message): void
    {

    }
}
