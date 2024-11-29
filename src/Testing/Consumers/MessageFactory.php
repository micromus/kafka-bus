<?php

namespace Micromus\KafkaBus\Testing\Consumers;

use Micromus\KafkaBus\Exceptions\TopicCannotResolvedException;
use Micromus\KafkaBus\Topics\TopicRegistry;
use RdKafka\Message;

class MessageFactory
{
    public function __construct(
        protected TopicRegistry $topicRegistry
    ) {
    }

    public function fromArray(array $attributes): Message
    {
        $message = new Message();
        $message->err = $attributes['err'] ?? RD_KAFKA_RESP_ERR_NO_ERROR;
        $message->key = $attributes['key'] ?? null;
        $message->headers = $attributes['headers'] ?? [];
        $message->payload = $attributes['payload'] ?? 'test-message';
        $message->offset = $attributes['offset'] ?? 0;
        $message->topic_name = $this->getTopicName($attributes['topic_name'] ?? 'test-topic-name');
        $message->partition = $attributes['partition'] ?? 0;
        $message->timestamp = $attributes['timestamp'] ?? time();

        return $message;
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
}
