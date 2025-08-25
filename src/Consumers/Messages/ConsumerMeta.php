<?php

namespace Micromus\KafkaBus\Consumers\Messages;

use RdKafka\Message;

final readonly class ConsumerMeta
{
    public function __construct(
        public Message $message
    ) {
    }
}
