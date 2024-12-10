<?php

namespace Micromus\KafkaBus\Interfaces\Producers\Messages;

interface ProducerMessageInterface
{
    public function toPayload(): string;
}
