<?php

namespace Micromus\KafkaBus\Bus\Listeners\Workers;

readonly class Route
{
    public function __construct(
        public string $topicKey,
        public string $handlerClass
    ) {
    }
}
