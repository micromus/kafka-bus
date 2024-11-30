<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Bus\Listeners\Workers\WorkerRoutes;
use Micromus\KafkaBus\Interfaces\ResolverInterface;
use Micromus\KafkaBus\Topics\TopicRegistry;

class ConsumerRouterFactory
{
    public function __construct(
        protected ResolverInterface $resolver,
        protected TopicRegistry $topicRegistry
    ) {
    }

    public function create(WorkerRoutes $routes): ConsumerRouter
    {
        $consumerRoutes = new ConsumerRoutes();
        $routesCollection = $routes->all();

        foreach ($routesCollection as $route) {
            $topicName = $this->topicRegistry->getTopicName($route->topicKey);
            $consumerRoutes->add(new Route($topicName, $route->handlerClass));
        }

        return new ConsumerRouter($this->resolver, $consumerRoutes);
    }
}
