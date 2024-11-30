<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Interfaces\Messages\MessageFactoryInterface;
use Micromus\KafkaBus\Interfaces\ResolverInterface;
use Micromus\KafkaBus\Messages\NativeMessageFactory;

final class MessageFactoryClassExtractor
{
    public function __construct(
        protected ResolverInterface $resolver,
    ) {
    }

    public function extract(mixed $handler): MessageFactoryInterface
    {
        $reflectionObject = new \ReflectionObject($handler);
        $attributes = $reflectionObject->getMethod('execute')
            ->getAttributes(MessageFactory::class);

        if (count($attributes) > 0) {
            return $this->resolver
                ->resolve($attributes[0]->newInstance()->messageClass);
        }

        return new NativeMessageFactory();
    }
}
