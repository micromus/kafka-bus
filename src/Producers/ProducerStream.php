<?php

namespace Micromus\KafkaBus\Producers;

use Micromus\KafkaBus\Contracts\Messages\HasPartition;
use Micromus\KafkaBus\Contracts\Messages\Message;
use Micromus\KafkaBus\Contracts\Messages\HasHeaders;
use Micromus\KafkaBus\Contracts\Messages\MessagePipeline;
use Micromus\KafkaBus\Contracts\Producers\Producer as ProducerContract;
use Micromus\KafkaBus\Contracts\Producers\ProducerStream as ProducerStreamContract;
use Micromus\KafkaBus\Producers\Messages\ProducerMessage;

class ProducerStream implements ProducerStreamContract
{
    public function __construct(
        protected ProducerContract $producer,
        protected MessagePipeline $messagePipeline
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
            headers: $message instanceof HasHeaders ? $message->getHeaders() : [],
            partition: $message instanceof HasPartition ? $message->getPartition() : RD_KAFKA_PARTITION_UA,
        );
    }
}
