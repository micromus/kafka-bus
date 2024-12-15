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
    /**
     * @param mixed $handler
     * @return class-string
     *
     * @throws ReflectionException
     */
    public function extract(mixed $handler): string
    {
        $reflectionObject = new ReflectionObject($handler);
        $attributes = $reflectionObject->getMethod('execute')
            ->getAttributes(MessageFactory::class);

        if (count($attributes) > 0) {
            return $attributes[0]->newInstance()->messageClass;
        }

        return NativeMessageFactory::class;
    }
}
