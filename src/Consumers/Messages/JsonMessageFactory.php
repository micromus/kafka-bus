<?php

namespace Micromus\KafkaBus\Consumers\Messages;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;

final class JsonMessageFactory implements MessageFactoryInterface
{
    /**
     * @param ConsumerMessageInterface $message
     * @return array<string|int, mixed>
     */
    public function fromKafka(ConsumerMessageInterface $message): array
    {
        return json_decode($message->payload(), true); // @phpstan-ignore-line
    }
}
