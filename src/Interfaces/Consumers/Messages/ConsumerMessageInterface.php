<?php

namespace Micromus\KafkaBus\Interfaces\Consumers\Messages;

use RdKafka\Message;

interface ConsumerMessageInterface
{
    public function msgId(): string;

    public function topicName(): string;

    public function key(): ?string;

    public function payload(): string;

    public function headers(): array;

    public function original(): Message;
}
