<?php

namespace Micromus\KafkaBus\Testing\Messages;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;

class ConsumerHandlerFaker
{
    public function execute(ConsumerMessage $message): void
    {
    }
}
