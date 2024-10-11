<?php

namespace Micromus\KafkaBus\Testing;

use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;
use Micromus\KafkaBus\Testing\Connections\ConnectionFaker;

class ProducerFaker implements ProducerInterface
{
    public function __construct(
        protected ConnectionFaker $connection,
        protected string          $topicName,
    ) {
    }

    public function produce(array $messages): void
    {
        $this->connection->publishedMessages[$this->topicName] = [
            ...$this->connection->publishedMessages[$this->topicName] ?? [],
            ...$messages,
        ];
    }
}
