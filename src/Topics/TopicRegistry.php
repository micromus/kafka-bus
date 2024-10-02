<?php

namespace Micromus\KafkaBus\Topics;

use Micromus\KafkaBus\Exceptions\TopicCannotResolvedException;

class TopicRegistry
{
    /**
     * @var Topic[]
     */
    protected array $topics = [];

    public function add(Topic $topic): self
    {
        $this->topics[$topic->key] = $topic;

        return $this;
    }

    public function getTopicName(string $topicKey): string
    {
        return $this->get($topicKey)->name;
    }

    public function get(string $topicKey): Topic
    {
        return $this->topics[$topicKey]
            ?? throw new TopicCannotResolvedException("Topic [$topicKey] not found");
    }
}
