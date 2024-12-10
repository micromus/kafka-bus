<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Consumers\Messages\NativeMessageFactory;

#[\Attribute]
final readonly class MessageFactory
{
    public function __construct(
        public string $messageClass = NativeMessageFactory::class
    ) {
    }
}
