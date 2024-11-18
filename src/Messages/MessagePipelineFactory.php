<?php

namespace Micromus\KafkaBus\Messages;

use Micromus\KafkaBus\Interfaces\Messages\MessagePipelineInterface;
use Micromus\KafkaBus\Interfaces\Messages\MessagePipelineFactoryInterface;
use Micromus\KafkaBus\Interfaces\ResolverInterface;

class MessagePipelineFactory implements MessagePipelineFactoryInterface
{
    public function __construct(
        protected ResolverInterface $resolver
    ) {
    }

    public function create(array $middlewares): MessagePipelineInterface
    {
        return new MessagePipeline($this->prepareMiddlewares($middlewares));
    }

    private function prepareMiddlewares(array $middlewares): array
    {
        return array_map(fn ($middleware) => $this->resolver->resolve($middleware), $middlewares);
    }
}
