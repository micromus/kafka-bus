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
        $this->routes[$route->topic->key] = $route;

        return $this;
    }

    public function has(string $topicKey): bool
    {
        return isset($this->routes[$topicKey]);
    }

    /**
     * @return Route[]
     */
    public function all(): array
    {
        return $this->routes;
    }
}
