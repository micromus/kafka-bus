<?php

namespace Micromus\KafkaBus\Support\Resolvers;

use Micromus\KafkaBus\Contracts\Resolver;

class NativeResolver implements Resolver
{
    public function resolve(string $class): mixed
    {
        return new $class;
    }
}
