<?php

namespace Micromus\KafkaBus\Testing\Consumers;

use Micromus\KafkaBus\Topics\TopicRegistry;
use RdKafka\Message;

class MessageBuilder
{
    protected array $state = [
        'err' => RD_KAFKA_RESP_ERR_NO_ERROR,
    ];

    public function __construct(
        protected MessageFactory $messageFactory
    ) {
    }

    public static function for(TopicRegistry $topicRegistry): self
    {
        return new MessageBuilder(new MessageFactory($topicRegistry));
    }

    public function build(array $extra = []): Message
    {
        return $this->messageFactory
            ->fromArray(array_merge($this->state, $extra));
    }
}
