<?php

namespace Micromus\KafkaBus\Consumers\Router\Extractors;

use Micromus\KafkaBus\Consumers\Attributes\MessageMiddleware;
use ReflectionException;
use ReflectionObject;

final class MiddlewareClassExtractor
{
    /**
     * @param mixed $handler
     * @return class-string[]
     *
     * @throws ReflectionException
     */
    public function extract(mixed $handler): array
    {
        $reflectionObject = new ReflectionObject($handler);
        $attributes = $reflectionObject->getMethod('execute')
            ->getAttributes(MessageMiddleware::class);

        return array_map($this->extractClass(...), $attributes);
    }

    private function extractClass(\ReflectionAttribute $attribute): string
    {
        return $attribute->newInstance()->middlewareClass;
    }
}
