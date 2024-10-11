<?php

namespace Micromus\KafkaBus\Messages;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Micromus\KafkaBus\Interfaces\Messages\MessageFactoryInterface;

class NativeMessageFactory implements MessageFactoryInterface
{
    public function fromKafka(ConsumerMessage $message): ConsumerMessage
    {
        return $message;
    }
}
