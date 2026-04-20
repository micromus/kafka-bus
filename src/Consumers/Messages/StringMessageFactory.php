<?php

namespace Micromus\KafkaBus\Consumers\Messages;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;

final class StringMessageFactory implements MessageFactoryInterface
{
    public function fromKafka(ConsumerMessageInterface $message): string
    {
        return $message->payload();
    }
}
