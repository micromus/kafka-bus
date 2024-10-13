<?php

namespace Micromus\KafkaBus\Testing\Messages;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;

class VoidConsumerHandlerFaker
{
    public function execute(ConsumerMessage $message): void
    {

    }
}
