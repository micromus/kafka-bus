<?php

namespace Micromus\KafkaBus\Interfaces\Consumers\Messages;

use Closure;

interface ConsumerMiddlewareInterface
{
    /**
     * @param ConsumerMessageInterface $message
     * @param Closure(ConsumerMessageInterface): void  $next
     * @return void
     */
    public function handle(ConsumerMessageInterface $message, Closure $next): void;
}
