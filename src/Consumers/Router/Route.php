<?php

namespace Micromus\KafkaBus\Consumers\Router;

readonly class Route
{
    public function __construct(
        public string $topicKey,
        public string $handlerClass
    ) {
    }
}
