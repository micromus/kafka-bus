<?php

namespace Micromus\KafkaBus\Contracts;

interface Resolver
{
    /**
     * @template T
     * @param class-string<T> $class
     * @return T
     */
    public function resolve(string $class): mixed;
}
