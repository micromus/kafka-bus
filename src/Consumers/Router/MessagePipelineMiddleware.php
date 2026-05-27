<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Consumers\Pipelines\MessagePipelineHandler;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineMiddlewareInterface;

/**
 * @extends PipelineMiddlewareInterface<MessagePipelineHandler>
 */
interface MessagePipelineMiddleware extends PipelineMiddlewareInterface
{
}
