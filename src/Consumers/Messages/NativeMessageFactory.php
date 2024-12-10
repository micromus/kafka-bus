<?php

namespace Micromus\KafkaBus\Consumers\Messages;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;

class NativeMessageFactory implements MessageFactoryInterface
{
    public function fromKafka(ConsumerMessageInterface $message): ConsumerMessageInterface
    {
        return $message;
    }
}
