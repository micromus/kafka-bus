<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Bus\Listeners\Router\Routes;
use Micromus\KafkaBus\Contracts\Resolver;
use Micromus\KafkaBus\Contracts\TopicNameResolver;

class ConsumerRouterFactory
{
    public function __construct(
        protected Resolver $resolver,
        protected TopicNameResolver $topicNameResolver
    ) {
    }

    public function create(Routes $routes): ConsumerRouter
    {
        $consumerRoutes = new ConsumerRoutes();
        $routesCollection = $routes->all();

        foreach ($routesCollection as $route) {
            $consumerRoutes->add(
                topicName: $this->topicNameResolver->resolve($route->topicKey),
                handlerClass: $route->handlerClass,
                messageFactory: $route->messageFactoryClass
            );
        }

        return new ConsumerRouter($this->resolver, $this->topicNameResolver, $consumerRoutes);
    }
}
