<?php

namespace Micromus\KafkaBus\Bus\Publishers;

use Micromus\KafkaBus\Bus\Publishers\Router\PublisherRouter;
use Micromus\KafkaBus\Bus\Publishers\Router\PublisherRoutes;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Producers\ProducerStreamFactory;
use Micromus\KafkaBus\Topics\TopicRegistry;

class PublisherFactory
{
    public function __construct(
        protected ProducerStreamFactory $factory,
        protected TopicRegistry $topicRegistry,
        protected PublisherRoutes $routes = new PublisherRoutes(),
    ) {
    }

    public function create(ConnectionInterface $connection): Publisher
    {
        return new Publisher(
            new PublisherRouter(
                $connection,
                $this->factory,
                $this->topicRegistry,
                $this->routes
            )
        );
    }
}
