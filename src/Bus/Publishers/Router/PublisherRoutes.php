<?php

namespace Micromus\KafkaBus\Bus\Publishers\Router;

use Micromus\KafkaBus\Bus\Publishers\Options;

class PublisherRoutes
{
    /**
     * @var array<class-string, Route>
     */
    protected array $routes = [];

    public function add(string $messageClass, string $topicKey, Options $options = new Options()): self
    {
        $this->routes[$messageClass] = new Route($topicKey, $options);

        return $this;
    }

    public function get(string $messageClass): ?Route
    {
        return $this->routes[$messageClass] ?? null;
    }
}
