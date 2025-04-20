<?php

namespace Micromus\KafkaBus\Bus\Listeners\Workers;

use Micromus\KafkaBus\Topics\Topic;

readonly class Route
{
    public function __construct(
        public Topic $topic,
        public string $handlerClass
    ) {
    }
}
