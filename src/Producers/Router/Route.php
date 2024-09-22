<?php

namespace Micromus\KafkaBus\Producers\Router;

readonly class Route
{
    public function __construct(
        public string $topicKey,
        public array $options = []
    ) {}
}
