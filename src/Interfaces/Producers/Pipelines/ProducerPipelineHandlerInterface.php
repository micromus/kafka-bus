<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Interfaces\Producers\Pipelines;

use Micromus\KafkaBus\Interfaces\Pipelines\PipelineHandlerInterface;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;
use Micromus\KafkaBus\Producers\Messages\ProducerMessage;

/**
 * @extends PipelineHandlerInterface<ProducerMessageInterface, ProducerMessage>
 */
interface ProducerPipelineHandlerInterface extends PipelineHandlerInterface
{
    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    public function withHeader(string $key, mixed $value): self;
}
