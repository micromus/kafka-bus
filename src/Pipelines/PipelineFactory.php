<?php

namespace Micromus\KafkaBus\Pipelines;

use Micromus\KafkaBus\Interfaces\Pipelines\PipelineInterface;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineFactoryInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class PipelineFactory implements PipelineFactoryInterface
{
    public function __construct(
        protected ContainerInterface $container
    ) {
    }

    /**
     * @param array $middlewares
     * @return PipelineInterface
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function create(array $middlewares): PipelineInterface
    {
        return new Pipeline($this->prepareMiddlewares($middlewares));
    }

    /**
     * @param array $middlewares
     * @return array
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function prepareMiddlewares(array $middlewares): array
    {
        return array_map(fn ($middleware) => $this->container->get($middleware), $middlewares);
    }
}
