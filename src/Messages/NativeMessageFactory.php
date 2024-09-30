<?php

namespace Micromus\KafkaBus\Messages;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Micromus\KafkaBus\Contracts\Messages\MessageFactory;

class NativeMessageFactory implements MessageFactory
{
    public function fromKafka(ConsumerMessage $message): ConsumerMessage
    {
        return $message;
    }
}
