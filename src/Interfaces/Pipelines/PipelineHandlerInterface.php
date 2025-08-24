<?php

namespace Micromus\KafkaBus\Interfaces\Pipelines;

/**
 * @template-covariant TTarget
 * @template-covariant TResult
 */
interface PipelineHandlerInterface
{
    /**
     * @return TTarget
     */
    public function target(): mixed;

    /**
     * @return TResult
     */
    public function handle(): mixed;
}
