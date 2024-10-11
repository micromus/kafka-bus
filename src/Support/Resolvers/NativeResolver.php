<?php

namespace Micromus\KafkaBus\Support\Resolvers;

use Micromus\KafkaBus\Interfaces\ResolverInterface;

class NativeResolver implements ResolverInterface
{
    public function resolve(string $class): mixed
    {
        return new $class();
    }
}
