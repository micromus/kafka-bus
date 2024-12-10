<?php

namespace Micromus\KafkaBus\Consumers\Router\Extractors;

use Micromus\KafkaBus\Consumers\Attributes\MessageFactory;
use Micromus\KafkaBus\Consumers\Messages\NativeMessageFactory;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;
use Micromus\KafkaBus\Interfaces\ResolverInterface;
use ReflectionException;
use ReflectionObject;

final class MessageFactoryClassExtractor
{
    public function __construct(
        protected ResolverInterface $resolver,
    ) {
    }

    /**
     * @param mixed $handler
     * @return MessageFactoryInterface
     *
     * @throws ReflectionException
     */
    public function extract(mixed $handler): MessageFactoryInterface
    {
        $reflectionObject = new ReflectionObject($handler);
        $attributes = $reflectionObject->getMethod('execute')
            ->getAttributes(MessageFactory::class);

        if (count($attributes) > 0) {
            return $this->resolver
                ->resolve($attributes[0]->newInstance()->messageClass);
        }

        return new NativeMessageFactory();
    }
}
