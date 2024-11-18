<?php

namespace Micromus\KafkaBus\Exceptions\Consumers;

use Exception;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Throwable;

class MessageConsumerNotHandledException extends Exception
{
    public function __construct(public readonly ConsumerMessage $consumerMessage, ?Throwable $previous = null)
    {
        $message = "Message #{$this->consumerMessage->msgId()} from ".
            "{$this->consumerMessage->topicName()} not handled";

        parent::__construct($message, 500, $previous);
    }
}
