<?php

namespace Micromus\KafkaBus\Consumers\Messages;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\WorkerConsumerMessageInterface;
use RdKafka\Message;

class WorkerConsumerMessage implements WorkerConsumerMessageInterface
{
    public function __construct(
        protected string $workerName,
        protected ConsumerMessageInterface $consumerMessage
    ) {
    }

    public function workerName(): string
    {
        return $this->workerName;
    }

    public function msgId(): string
    {
        return $this->consumerMessage->msgId();
    }

    public function topicName(): string
    {
        return $this->consumerMessage->topicName();
    }

    public function key(): ?string
    {
        return $this->consumerMessage->key();
    }

    public function payload(): string
    {
        return $this->consumerMessage->payload();
    }

    public function headers(): array
    {
        return $this->consumerMessage->headers();
    }

    public function original(): Message
    {
        return $this->consumerMessage->original();
    }
}
