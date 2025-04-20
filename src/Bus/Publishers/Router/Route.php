<?php

namespace Micromus\KafkaBus\Bus\Publishers\Router;

use Micromus\KafkaBus\Topics\Topic;

readonly class Route
{
    public function __construct(
        public string $messageClass,
        public Topic $topic,
        public Options $options = new Options()
    ) {
    }
}
