<?php

namespace Micromus\KafkaBus\Consumers\Messages;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;
use RdKafka\Message;

final class OriginalMessageFactory implements MessageFactoryInterface
{
    public function fromKafka(ConsumerMessageInterface $message): Message
    {
        return $message->original();
    }
}
