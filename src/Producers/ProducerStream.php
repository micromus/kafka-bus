<?php

namespace Micromus\KafkaBus\Producers;

use Micromus\KafkaBus\Contracts\Messages\HasHeaders;
use Micromus\KafkaBus\Contracts\Messages\HasPartition;
use Micromus\KafkaBus\Contracts\Messages\Message;
use Micromus\KafkaBus\Contracts\Messages\MessagePipeline;
use Micromus\KafkaBus\Contracts\Producers\Producer as ProducerContract;
use Micromus\KafkaBus\Contracts\Producers\ProducerStream as ProducerStreamContract;
use Micromus\KafkaBus\Producers\Messages\ProducerMessage;
use Micromus\KafkaBus\Topics\Topic;

class ProducerStream implements ProducerStreamContract
{
    public function __construct(
        protected ProducerContract $producer,
        protected MessagePipeline $messagePipeline,
        protected Topic $topic
    ) {}

    public function handle(array $messages): void
    {
        $producerMessages = array_map($this->handleMessage(...), $messages);

        $this->producer
            ->produce($producerMessages);
    }

    private function handleMessage(Message $message): ProducerMessage
    {
        return $this->messagePipeline
            ->then($message, $this->mapProducerMessage(...));
    }

    private function mapProducerMessage(Message $message): ProducerMessage
    {
        return new ProducerMessage(
            payload: $message->toPayload(),
            headers: $this->getHeadersFromMessage($message),
            partition: max($this->getPartitionFromMessage($message), RD_KAFKA_PARTITION_UA),
        );
    }

    private function getHeadersFromMessage(Message $message): array
    {
        return $message instanceof HasHeaders
            ? $message->getHeaders()
            : [];
    }

    private function getPartitionFromMessage(Message $message): int
    {
        return $message instanceof HasPartition
            ? min($message->getPartition($this->topic->partitions), $this->topic->partitions - 1)
            : RD_KAFKA_PARTITION_UA;
    }
}
