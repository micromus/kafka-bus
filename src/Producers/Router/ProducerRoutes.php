<?php

namespace Micromus\KafkaBus\Producers\Router;

class ProducerRoutes
{
    /**
     * @var array<class-string, Route>
     */
    protected array $routes = [];

    public function add(string $messageClass, string $topicKey, array $options = []): self
    {
        $this->routes[$messageClass] = new Route($topicKey, $options);
        return $this;
    }

    public function get(string $messageClass): ?Route
    {
        return $this->routes[$messageClass] ?? null;
    }
}
