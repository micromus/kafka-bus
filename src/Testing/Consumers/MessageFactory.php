<?php

namespace Micromus\KafkaBus\Testing\Consumers;

use Micromus\KafkaBus\Exceptions\TopicCannotResolvedException;
use Micromus\KafkaBus\Topics\TopicRegistry;
use RdKafka\Message;

class MessageFactory
{
    /**
     * @var array<string, string>
     */
    protected array $headers = [];

    protected string|null $key = null;

    protected string $topicKey = 'test';

    protected string $connection = 'default';

    public function __construct(
        protected TopicRegistry $topicRegistry
    ) {
    }

    public static function for(TopicRegistry $topicRegistry): MessageFactory
    {
        return new self($topicRegistry);
    }

    private function getTopicName(string $topicKey): string
    {
        try {
            return $this->topicRegistry->getTopicName($topicKey);
        }
        catch (TopicCannotResolvedException) {
            return $topicKey;
        }
    }

    /**
     * @param array<string, string> $headers
     * @return static
     */
    public function withHeaders(array $headers): static
    {
        return $this->immutableSet('headers', $headers);
    }

    public function withKey(?string $key): static
    {
        return $this->immutableSet('key', $key);
    }

    public function withTopicKey(?string $topicKey): static
    {
        return $this->immutableSet('topicKey', $topicKey);
    }

    public function withConnection(string $connection): static
    {
        return $this->immutableSet('connection', $connection);
    }

    private function immutableSet(string $field, mixed $value): static
    {
        $clone = clone $this;
        $clone->$field = $value;

        return $clone;
    }

    public function make(string $payload): Message
    {
        $message = new Message();
        $message->err = RD_KAFKA_RESP_ERR_NO_ERROR;
        $message->payload = $payload;
        $message->headers = $this->headers;
        $message->key = $this->key ?: '';

        $message->topic_name = $this->getTopicName($this->topicKey);

        return $message;
    }

    /**
     * @param array<string, mixed> $attributes
     * @return Message
     *
     * @throws \JsonException
     */

    public function fromArray(array $attributes): Message
    {
        return $this->make(\json_encode($attributes, JSON_THROW_ON_ERROR));
    }
}
