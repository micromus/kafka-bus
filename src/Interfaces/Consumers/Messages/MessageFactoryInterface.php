<?php

namespace Micromus\KafkaBus\Interfaces\Consumers\Messages;

interface MessageFactoryInterface
{
    public function fromKafka(ConsumerMessageInterface $message): mixed;
}
