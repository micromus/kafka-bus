<?php

namespace Micromus\KafkaBus\Producers\Pipelines;

use Micromus\KafkaBus\Interfaces\Pipelines\PipelineMiddlewareInterface;

/**
 * @extends PipelineMiddlewareInterface<ProducerPipelineHandler>
 */
interface ProducerPipelineMiddleware extends PipelineMiddlewareInterface
{
}
