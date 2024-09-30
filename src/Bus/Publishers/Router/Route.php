<?php

namespace Micromus\KafkaBus\Bus\Publishers\Router;

use Micromus\KafkaBus\Bus\Publishers\Options;

readonly class Route
{
    public function __construct(
        public string $topicKey,
        public Options $options
    ) {}
}
