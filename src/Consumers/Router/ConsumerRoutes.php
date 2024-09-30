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

    public function add(string $topicName, string $handlerClass, string $messageFactory): self
    {
        $this->routes[$topicName] = new Route($handlerClass, $messageFactory);

        return $this;
    }

    public function get(string $topicName): ?Route
    {
        return $this->routes[$topicName] ?? null;
    }
}
