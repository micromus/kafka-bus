<?php

namespace Micromus\KafkaBus\Producers;

use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;
use Micromus\KafkaBus\Exceptions\Producers\CannotFlushProducerException;
use Micromus\KafkaBus\Producers\Messages\ProducerMessage;
use Micromus\KafkaBus\Support\RetryRepeater;
use RdKafka\Producer as KafkaProducer;
use RdKafka\ProducerTopic;

class Producer implements ProducerInterface
{
    protected ProducerTopic $topic;

    public function __construct(
        protected KafkaProducer $producer,
        protected string $topicName,
        protected RetryRepeater $retryRepeater = new RetryRepeater(),
        protected int $timeout = 2000
    ) {
        $this->topic = $this->producer
            ->newTopic($this->topicName);
    }

    /**
     * @param  ProducerMessage[]  $messages
     */
    public function produce(array $messages): void
    {
        foreach ($messages as $message) {
            $this->poll($message);
        }

        $this->flush();
    }

    private function poll(ProducerMessage $producerMessage): void
    {
        $this->topic->producev(
            partition: $producerMessage->partition,
            msgflags: RD_KAFKA_MSG_F_BLOCK,
            payload: $producerMessage->payload,
            key: $producerMessage->key,
            headers: $producerMessage->headers
        );

        $this->producer->poll(0);
    }

    private function flush(): void
    {
        $this->retryRepeater
            ->execute(fn () => $this->attemptFlush());
    }

    /**
     * @throws CannotFlushProducerException
     */
    private function attemptFlush(): void
    {
        $result = $this->producer->flush($this->timeout);

        if ($result === RD_KAFKA_RESP_ERR_NO_ERROR) {
            return;
        }

        throw new CannotFlushProducerException($result);
    }
}
