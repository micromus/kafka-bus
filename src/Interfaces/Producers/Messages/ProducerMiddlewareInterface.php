<?php

namespace Micromus\KafkaBus\Interfaces\Producers\Messages;

use Closure;
use Micromus\KafkaBus\Producers\Messages\ProducerMessage;

interface ProducerMiddlewareInterface
{
    /**
     * @param ProducerMessageInterface $message
     * @param Closure(ProducerMessageInterface): ?ProducerMessage $next
     * @return ProducerMessage|null
     */
    public function handle(ProducerMessageInterface $message, Closure $next): ?ProducerMessage;
}
