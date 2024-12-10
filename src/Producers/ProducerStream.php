<?php

namespace Micromus\KafkaBus\Producers;

use Micromus\KafkaBus\Interfaces\Messages\MessagePipelineInterface;
use Micromus\KafkaBus\Interfaces\Producers\Messages\HasHeaders;
use Micromus\KafkaBus\Interfaces\Producers\Messages\HasKey;
use Micromus\KafkaBus\Interfaces\Producers\Messages\HasPartition;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerStreamInterface;
use Micromus\KafkaBus\Producers\Messages\ProducerMessage;
use Micromus\KafkaBus\Topics\Topic;

class ProducerStream implements ProducerStreamInterface
{
    public function __construct(
        protected ProducerInterface         $producer,
        protected MessagePipelineInterface $messagePipeline,
        protected Topic                    $topic
    ) {
    }

    public function handle(array $messages): void
    {
        $producerMessages = array_filter(array_map($this->handleMessage(...), $messages));

        $this->producer
            ->produce($producerMessages);
    }

    private function handleMessage(ProducerMessageInterface $message): ProducerMessage
    {
        return $this->messagePipeline
            ->then($message, $this->mapProducerMessage(...));
    }


    private function mapProducerMessage(ProducerMessageInterface $message): ProducerMessage
    {
        return new ProducerMessage(
            payload: $message->toPayload(),
            headers: $this->getHeadersFromMessage($message),
            partition: $this->getPartitionFromMessage($message),
            key: $this->getKey($message)
        );
    }

    private function getHeadersFromMessage(ProducerMessageInterface $message): array
    {
        return $message instanceof HasHeaders
            ? $message->getHeaders()
            : [];
    }

    private function getKey(ProducerMessageInterface $message): ?string
    {
        return $message instanceof HasKey
            ? $message->getKey()
            : null;
    }

    private function getPartitionFromMessage(ProducerMessageInterface $message): int
    {
        return $message instanceof HasPartition
            ? max($message->getPartition(), RD_KAFKA_PARTITION_UA)
            : RD_KAFKA_PARTITION_UA;
    }
}
