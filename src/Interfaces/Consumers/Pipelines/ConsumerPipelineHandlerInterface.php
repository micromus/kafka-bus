<?php

namespace Micromus\KafkaBus\Interfaces\Consumers\Pipelines;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\WorkerConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineHandlerInterface;

/**
 * @extends PipelineHandlerInterface<WorkerConsumerMessageInterface, WorkerConsumerMessageInterface>
 */
interface ConsumerPipelineHandlerInterface extends PipelineHandlerInterface
{
}
