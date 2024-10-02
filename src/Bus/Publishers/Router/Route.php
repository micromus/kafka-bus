<?php

namespace Micromus\KafkaBus\Bus\Publishers\Router;

readonly class Route
{
    public function __construct(
        public string $topicKey,
        public Options $options
    ) {}
}
