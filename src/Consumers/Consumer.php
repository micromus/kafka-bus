<?php

namespace Micromus\KafkaBus\Consumers;

use Micromus\KafkaBus\Consumers\Commiters\CommiterInterface;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessageConverter;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerException;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Support\RetryRepeater;
use RdKafka\Exception;
use RdKafka\KafkaConsumer;

class Consumer implements ConsumerInterface
{
    protected ConsumerMessageConverter $consumerMessageNormalizer;

    public function __construct(
        protected KafkaConsumer     $consumer,
        protected array             $topicNames,
        protected CommiterInterface $commiter,
        protected RetryRepeater     $retryRepeater = new RetryRepeater(),
        protected int               $consumerTimeout = 2000
    ) {
        $this->consumerMessageNormalizer = new ConsumerMessageConverter();
        $this->consumer->subscribe($this->topicNames);
    }

    public function __destruct()
    {
        $this->consumer->unsubscribe();
        $this->consumer->close();
    }

    public function getMessage(): ConsumerMessageInterface
    {
        try {
            $message = $this->consumer
                ->consume($this->consumerTimeout);

            if ($message->err !== RD_KAFKA_RESP_ERR_NO_ERROR) {
                throw new MessageConsumerException($message);
            }

            return $this->consumerMessageNormalizer
                ->fromKafka($message);
        }
        catch (Exception $exception) {
            throw new ConsumerException($exception->getMessage(), $exception->getCode(), $exception);
        }
    }

    public function commit(ConsumerMessageInterface $consumerMessage): void
    {
        $this->retryRepeater
            ->execute(fn () => $this->commiter->commit($consumerMessage));
    }
}
