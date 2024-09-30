<?php

namespace Micromus\KafkaBus\Testing;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;

class ConsumerHandlerFaker
{
    public function execute(ConsumerMessage $message): void {}
}
