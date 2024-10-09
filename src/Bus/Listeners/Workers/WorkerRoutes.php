<?php

namespace Micromus\KafkaBus\Bus\Listeners\Workers;

class WorkerRoutes
{
    /**
     * @var Route[]
     */
    protected array $routes = [];

    public function add(Route $route): self
    {
        $this->routes[$route->topicKey] = $route;

        return $this;
    }

    /**
     * @return Route[]
     */
    public function all(): array
    {
        return $this->routes;
    }
}
