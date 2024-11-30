<?php

namespace Micromus\KafkaBus\Consumers\Router;

class ConsumerRoutes
{
    /**
     * @var array<class-string, Route>
     */
    protected array $routes = [];

    public function topics(): array
    {
        return array_keys($this->routes);
    }

    public function add(Route $route): self
    {
        $this->routes[$route->topicKey] = $route;

        return $this;
    }

    public function get(string $topicName): ?Route
    {
        return $this->routes[$topicName] ?? null;
    }
}
