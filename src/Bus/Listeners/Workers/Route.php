<?php

namespace Micromus\KafkaBus\Bus\Listeners\Workers;

use Micromus\KafkaBus\Messages\NativeMessageFactory;

readonly class Route
{
    public function __construct(
        public string $topicKey,
        public string $handlerClass,
        public string $messageFactoryClass = NativeMessageFactory::class,
    ) {
    }
}
