<?php

namespace Micromus\KafkaBus\Consumers\Attributes;

use Attribute;
use Micromus\KafkaBus\Consumers\Messages\NativeMessageFactory;

#[Attribute(Attribute::TARGET_METHOD)]
final readonly class MessageFactory
{
    public function __construct(
        public string $messageClass = NativeMessageFactory::class
    ) {
    }
}
