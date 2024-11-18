<?php

namespace Micromus\KafkaBus\Consumers\Messages;

class ConsumerMessage
{
    public function __construct(
        public string $payload,
        public array $headers,
        public readonly ConsumerMeta $meta
    ) {
    }

    public function topicName(): string
    {
        return $this->meta->message->topic_name;
    }

    public function key(): ?string
    {
        return $this->meta->message->key;
    }

    public function msgId(): string
    {
        return "{$this->meta->message->partition}-{$this->meta->message->offset}";
    }
}
