<?php

namespace Micromus\KafkaBus\Consumers\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
final readonly class MessageMiddleware
{
    public function __construct(
        public string $middlewareClass
    ) {
    }
}
