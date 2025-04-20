<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Bus\Listeners\Workers\WorkerRoutes;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineFactoryInterface;
use Psr\Container\ContainerInterface;

class ConsumerRouterFactory
{
    public function __construct(
        protected ContainerInterface $container,
        protected PipelineFactoryInterface $pipelineFactory
    ) {
    }

    public function create(WorkerRoutes $routes): ConsumerRouter
    {
        $consumerRoutes = new ConsumerRoutes();

        foreach ($routes->all() as $route) {
            $consumerRoutes->add(new Route($route->topic, $route->handlerClass));
        }

        return new ConsumerRouter($this->container, $this->pipelineFactory, $consumerRoutes);
    }
}
