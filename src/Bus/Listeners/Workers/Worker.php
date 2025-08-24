<?php

namespace Micromus\KafkaBus\Bus\Listeners\Workers;

use Micromus\KafkaBus\Consumers\Router\ConsumerRoutes;
use Micromus\KafkaBus\Topics\Topic;

readonly class Worker
{
    public function __construct(
        public string $name,
        public ConsumerRoutes $routes,
        public Options $options = new Options()
    ) {
    }

    /**
     * @return list<Topic>
     */
    public function topics(): array
    {
        return $this->routes->topics();
    }
}
