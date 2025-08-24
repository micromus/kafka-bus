<?php

namespace Micromus\KafkaBus\Interfaces\Producers\Pipelines;

use Micromus\KafkaBus\Interfaces\Pipelines\PipelineMiddlewareInterface;

/**
 * @extends PipelineMiddlewareInterface<ProducerPipelineHandlerInterface>
 */
interface ProducerPipelineMiddlewareInterface extends PipelineMiddlewareInterface
{
}
