<?php

namespace Micromus\KafkaBus\Bus\Listeners\Workers;

readonly class Worker
{
    public function __construct(
        public string $name,
        public WorkerRoutes $routes,
        public Options $options = new Options()
    ) {
    }

    public function topics(): array
    {
        return array_map(
            fn (Route $route) => $route->topic,
            $this->routes->all()
        );
    }
}
