<?php

namespace Micromus\KafkaBus\Bus\Listeners;

use Micromus\KafkaBus\Bus\Listeners\Router\Routes;

readonly class Options
{
    public function __construct(
        public Routes $routes = new Routes,
        public array $additionalOptions = [],
        public bool $autoCommit = true,
        public int $consumerTimeout = 2000,
        public int $maxMessages = -1,
        public int $maxTime = -1
    ) {}
}
