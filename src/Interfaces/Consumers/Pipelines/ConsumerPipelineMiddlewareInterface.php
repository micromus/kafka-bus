<?php

namespace Micromus\KafkaBus\Interfaces\Consumers\Pipelines;

use Micromus\KafkaBus\Interfaces\Pipelines\PipelineMiddlewareInterface;

/**
 * @extends PipelineMiddlewareInterface<ConsumerPipelineHandlerInterface>
 */
interface ConsumerPipelineMiddlewareInterface extends PipelineMiddlewareInterface
{
}
