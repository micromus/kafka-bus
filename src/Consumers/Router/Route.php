<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Topics\Topic;

readonly class Route
{
    public function __construct(
        public Topic $topic,
        public string $handlerClass
    ) {
    }
}
