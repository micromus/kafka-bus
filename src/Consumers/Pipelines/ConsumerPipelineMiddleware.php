<?php

namespace Micromus\KafkaBus\Consumers\Pipelines;

use Micromus\KafkaBus\Interfaces\Pipelines\PipelineMiddlewareInterface;

/**
 * @extends PipelineMiddlewareInterface<ConsumerPipelineHandler>
 */
interface ConsumerPipelineMiddleware extends PipelineMiddlewareInterface
{
}
