<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Bus\Listeners\Workers\WorkerRoutes;
use Micromus\KafkaBus\Contracts\Resolver;
use Micromus\KafkaBus\Topics\TopicRegistry;

class ConsumerRouterFactory
{
    public function __construct(
        protected Resolver $resolver,
        protected TopicRegistry $topicRegistry
    ) {}

    public function create(WorkerRoutes $routes): ConsumerRouter
    {
        $consumerRoutes = new ConsumerRoutes;
        $routesCollection = $routes->all();

        foreach ($routesCollection as $route) {
            $consumerRoutes->add(
                topicName: $this->topicRegistry->getTopicName($route->topicKey),
                handlerClass: $route->handlerClass,
                messageFactory: $route->messageFactoryClass
            );
        }

        return new ConsumerRouter($this->resolver, $consumerRoutes);
    }
}
