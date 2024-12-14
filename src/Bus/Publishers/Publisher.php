<?php

namespace Micromus\KafkaBus\Bus\Publishers;

use Micromus\KafkaBus\Bus\Publishers\Router\PublisherRouter;

class Publisher
{
    public function __construct(
        protected PublisherRouter $router
    ) {
    }

    public function publish(iterable $messages): void
    {
        $this->router
            ->publish($messages);
    }
}
