<?php

namespace Micromus\KafkaBus\Interfaces\Consumers\Messages;

use RdKafka\Message;
use Stringable;

interface ConsumerMessageInterface
{
    public function msgId(): string;

    public function topicName(): string;

    public function key(): ?string;

    public function payload(): string;

    /**
     * @return array<string, mixed>
     */
    public function headers(): array;

    public function original(): Message;
}
