<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Messages\NativeMessageFactory;

#[\Attribute]
final readonly class MessageFactory
{
    public function __construct(
        public string $messageClass = NativeMessageFactory::class
    ) {
    }
}
