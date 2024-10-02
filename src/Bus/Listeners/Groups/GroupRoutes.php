<?php

namespace Micromus\KafkaBus\Bus\Listeners\Groups;

class GroupRoutes
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
