<?php

namespace Micromus\KafkaBus\Interfaces\Messages;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;

interface MessageFactoryInterface
{
    public function fromKafka(ConsumerMessage $message): mixed;
}
