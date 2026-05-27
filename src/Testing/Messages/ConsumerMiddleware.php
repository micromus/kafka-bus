<?php

namespace Micromus\KafkaBus\Testing\Messages;

use Micromus\KafkaBus\Consumers\Pipelines\ConsumerPipelineHandler;
use Micromus\KafkaBus\Consumers\Pipelines\ConsumerPipelineMiddleware;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineInterface;

/**
 * @internal
 */
final class ConsumerMiddleware implements ConsumerPipelineMiddleware
{
    /**
     * @param PipelineInterface<ConsumerPipelineHandler> $pipeline
     * @return PipelineInterface<ConsumerPipelineHandler>
     */
    public function handle(PipelineInterface $pipeline): PipelineInterface
    {
        echo $pipeline->handler()->target()->topicName();

        return $pipeline->continue();

    }
}
