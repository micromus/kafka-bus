<?php

namespace Micromus\KafkaBus\Interfaces;

interface ResolverInterface
{
    /**
     * @template T
     *
     * @param  class-string<T>  $class
     * @return T
     */
    public function resolve(string $class): mixed;
}
