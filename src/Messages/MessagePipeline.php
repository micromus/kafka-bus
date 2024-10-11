<?php

namespace Micromus\KafkaBus\Messages;

use Closure;
use Micromus\KafkaBus\Interfaces\Messages\MessagePipelineInterface;

class MessagePipeline implements MessagePipelineInterface
{
    public function __construct(
        protected array $middlewares = []
    ) {
    }

    public function then(mixed $message, Closure $destination): mixed
    {
        return $destination($message);
    }
}
