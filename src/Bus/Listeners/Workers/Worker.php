<?php

namespace Micromus\KafkaBus\Bus\Listeners\Workers;

readonly class Worker
{
    public function __construct(
        public string $name,
        public WorkerRoutes $routes,
        public Options $options = new Options(),
        public int $maxMessages = -1
    ) {
    }
}
