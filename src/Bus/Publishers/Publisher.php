<?php

namespace Micromus\KafkaBus\Bus\Publishers;

use Micromus\KafkaBus\Bus\Publishers\Router\PublisherRouter;
use Micromus\KafkaBus\Bus\Publishers\Router\Route;

class Publisher
{
    public function __construct(
        protected PublisherRouter $router
    ) {
    }

    /**
     * @return list<Route>
     */
    public function routes(): array
    {
        return $this->router
            ->routes();
    }

    public function publish(iterable $messages): void
    {
        $this->router
            ->publish($messages);
    }
}
