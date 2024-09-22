<?php

namespace Micromus\KafkaBus\Producers\Router;

use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Producers\ProducerStreamFactory;

class ProducerRouterFactory
{
    public function __construct(
        protected ProducerStreamFactory $producerStreamFactory,
        protected ProducerRoutes $routes
    ) {}

    public function create(Connection $connection): ProducerRouter
    {
        return new ProducerRouter(
            $connection,
            $this->producerStreamFactory,
            $this->routes
        );
    }
}
