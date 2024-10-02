<?php

namespace Micromus\KafkaBus\Bus\Listeners\Groups;

readonly class Group
{
    public function __construct(
        public GroupRoutes $routes,
        public Options $options = new Options,
        public int $maxMessages = -1,
        public int $maxTime = -1
    ) {}
}
