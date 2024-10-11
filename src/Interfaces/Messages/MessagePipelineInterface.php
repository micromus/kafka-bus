<?php

namespace Micromus\KafkaBus\Interfaces\Messages;

use Closure;

interface MessagePipelineInterface
{
    public function then(mixed $message, Closure $destination): mixed;
}
