<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Producers\Pipelines;

use Micromus\KafkaBus\Interfaces\Pipelines\PipelineHandlerInterface;
use Micromus\KafkaBus\Interfaces\Producers\Messages\HasHeaders;
use Micromus\KafkaBus\Interfaces\Producers\Messages\HasKey;
use Micromus\KafkaBus\Interfaces\Producers\Messages\HasPartition;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;
use Micromus\KafkaBus\Producers\Messages\ProducerMessage;
use Micromus\KafkaBus\Topics\Topic;

/**
 * @implements PipelineHandlerInterface<ProducerMessageInterface, ProducerMessage>
 */
final class ProducerPipelineHandler implements PipelineHandlerInterface
{
    /**
     * @var array<string, mixed>
     */
    protected array $headers = [];

    public function __construct(
        protected ProducerMessageInterface $target,
        protected Topic $topic,
    ) {
    }

    public function withHeader(string $key, mixed $value): self
    {
        $this->headers[$key] = $value;
        return $this;
    }

    public function target(): ProducerMessageInterface
    {
        return $this->target;
    }

    public function handle(): ProducerMessage
    {
        return new ProducerMessage(
            topic: $this->topic,
            payload: $this->target->toPayload(),
            headers: array_merge($this->getHeaders(), $this->headers),
            partition: $this->getPartition(),
            key: $this->getKey()
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function getHeaders(): array
    {
        return $this->target instanceof HasHeaders
            ? $this->target->getHeaders()
            : [];
    }

    private function getKey(): ?string
    {
        return $this->target instanceof HasKey
            ? $this->target->getKey()
            : null;
    }

    private function getPartition(): int
    {
        return $this->target instanceof HasPartition
            ? max($this->target->getPartition(), RD_KAFKA_PARTITION_UA)
            : RD_KAFKA_PARTITION_UA;
    }
}
