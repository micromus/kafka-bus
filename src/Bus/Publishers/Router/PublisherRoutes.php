<?php

namespace Micromus\KafkaBus\Bus\Publishers\Router;

class PublisherRoutes
{
    /**
     * @var array<class-string, Route>
     */
    protected array $routes = [];

    public function add(Route $route): self
    {
        $this->routes[$route->messageClass] = $route;

        return $this;
    }

    /**
     * @return list<Route>
     */
    public function all(): array
    {
        return array_values($this->routes);
    }

    public function get(string $messageClass): ?Route
    {
        return $this->routes[$messageClass] ?? null;
    }
}
