<?php

namespace Micromus\KafkaBus\Bus\Publishers;

use Micromus\KafkaBus\Bus\Publishers\Router\PublisherRouter;
use Micromus\KafkaBus\Bus\Publishers\Router\PublisherRoutes;
use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Producers\ProducerStreamFactory;

class PublisherFactory
{
    public function __construct(
        protected ProducerStreamFactory $factory,
        protected PublisherRoutes $routes = new PublisherRoutes(),
    ) {
    }

    public function create(Connection $connection): Publisher
    {
        return new Publisher(new PublisherRouter($connection, $this->factory, $this->routes));
    }
}
