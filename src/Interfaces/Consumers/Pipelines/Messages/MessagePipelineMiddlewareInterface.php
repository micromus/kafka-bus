<?php

namespace Micromus\KafkaBus\Interfaces\Consumers\Pipelines\Messages;

use Micromus\KafkaBus\Interfaces\Pipelines\PipelineMiddlewareInterface;

/**
 * @extends PipelineMiddlewareInterface<MessagePipelineHandlerInterface>
 */
interface MessagePipelineMiddlewareInterface extends PipelineMiddlewareInterface
{
}
