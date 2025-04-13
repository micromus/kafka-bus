<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Bus\Listeners\Workers\WorkerRoutes;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineFactoryInterface;
use Micromus\KafkaBus\Topics\TopicRegistry;
use Psr\Container\ContainerInterface;

class ConsumerRouterFactory
{
    public function __construct(
        protected ContainerInterface $container,
        protected PipelineFactoryInterface $pipelineFactory,
        protected TopicRegistry $topicRegistry
    ) {
    }

    public function create(WorkerRoutes $routes): ConsumerRouter
    {
        $consumerRoutes = new ConsumerRoutes();
        $routesCollection = $routes->all();

        foreach ($routesCollection as $route) {
            $topic = $this->topicRegistry->get($route->topicKey);
            $consumerRoutes->add(new Route($topic, $route->handlerClass));
        }

        return new ConsumerRouter($this->container, $this->pipelineFactory, $consumerRoutes);
    }
}
