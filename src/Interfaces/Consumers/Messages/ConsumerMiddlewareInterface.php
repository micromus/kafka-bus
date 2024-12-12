<?php

namespace Micromus\KafkaBus\Interfaces\Consumers\Messages;

use Closure;

interface ConsumerMiddlewareInterface
{
    /**
     * @param WorkerConsumerMessageInterface $message
     * @param Closure(WorkerConsumerMessageInterface): void  $next
     * @return void
     */
    public function handle(WorkerConsumerMessageInterface $message, Closure $next): void;
}
