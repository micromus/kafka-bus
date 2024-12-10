<?php

namespace Micromus\KafkaBus\Pipelines;

use Micromus\KafkaBus\Interfaces\Pipelines\PipelineInterface;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineFactoryInterface;
use Micromus\KafkaBus\Interfaces\ResolverInterface;

final class PipelineFactory implements PipelineFactoryInterface
{
    public function __construct(
        protected ResolverInterface $resolver
    ) {
    }

    public function create(array $middlewares): PipelineInterface
    {
        return new Pipeline($this->prepareMiddlewares($middlewares));
    }

    private function prepareMiddlewares(array $middlewares): array
    {
        return array_map(fn ($middleware) => $this->resolver->resolve($middleware), $middlewares);
    }
}
