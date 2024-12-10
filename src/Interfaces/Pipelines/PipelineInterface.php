<?php

namespace Micromus\KafkaBus\Interfaces\Pipelines;

use Closure;

interface PipelineInterface
{
    public function then(mixed $message, Closure $destination): mixed;
}
