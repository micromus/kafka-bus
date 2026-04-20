<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Topics\Topic;

final class ConsumerRoutes
{
    /**
     * @var array<string, Route>
     */
    protected array $routes = [];

    /**
     * @return list<Topic>
     */
    public function topics(): array
    {
        return array_column($this->routes, 'topic');
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
