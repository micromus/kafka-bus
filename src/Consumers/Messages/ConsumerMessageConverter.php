<?php

namespace Micromus\KafkaBus\Consumers\Messages;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use RdKafka\Message;

final class ConsumerMessageConverter
{
    public function fromKafka(Message $message): ConsumerMessageInterface
    {
        return new ConsumerMessage($message);
    }
}
