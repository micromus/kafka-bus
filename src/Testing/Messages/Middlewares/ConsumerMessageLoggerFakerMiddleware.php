<?php

namespace Micromus\KafkaBus\Testing\Messages\Middlewares;

use Closure;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;

final class ConsumerMessageLoggerFakerMiddleware
{
    public function handle(ConsumerMessageInterface $message, Closure $next): void
    {
        echo "Execute consumer message: {$message->msgId()}" . PHP_EOL;

        $next($message);

        echo "Consumer message executed." . PHP_EOL;
    }
}
