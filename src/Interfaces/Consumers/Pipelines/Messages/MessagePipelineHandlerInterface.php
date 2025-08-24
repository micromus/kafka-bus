<?php

namespace Micromus\KafkaBus\Interfaces\Consumers\Pipelines\Messages;

use Micromus\KafkaBus\Interfaces\Pipelines\PipelineHandlerInterface;

/**
 * @extends PipelineHandlerInterface<mixed, true>
 */
interface MessagePipelineHandlerInterface extends PipelineHandlerInterface
{
}
