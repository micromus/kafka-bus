<?php

namespace Micromus\KafkaBus\Contracts\Messages;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;

interface MessageFactory
{
    public function fromKafka(ConsumerMessage $message): mixed;
}
