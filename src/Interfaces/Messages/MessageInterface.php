<?php

namespace Micromus\KafkaBus\Interfaces\Messages;

interface MessageInterface
{
    public function toPayload(): string;
}
