<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Topics\Topic;

class ConsumerRoutes
{
    /**
     * @var array<string, Route>
     */
    protected array $routes = [];

    /**
     * @return Topic[]
     */
    public function topics(): array
    {
        return array_map(fn (Route $route) => $route->topic, $this->routes);
    }

    public function add(Route $route): self
    {
        $this->routes[$route->topic->name] = $route;

        return $this;
    }

    public function get(string $topicName): ?Route
    {
        return $this->routes[$topicName] ?? null;
    }
}
