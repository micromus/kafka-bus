<?php

namespace Micromus\KafkaBus\Testing;

use Micromus\KafkaBus\Contracts\Producers\Producer;

class ProducerFaker implements Producer
{
    public function __construct(
        protected ConnectionFaker $connection,
        protected string $topicName,
    ) {}

    public function produce(array $messages): void
    {
        $this->connection->publishedMessages[$this->topicName] = [
            ...$this->connection->publishedMessages[$this->topicName] ?? [],
            ...$messages,
        ];
    }
}
