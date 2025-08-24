<?php

namespace Micromus\KafkaBus\Consumers;

use Micromus\KafkaBus\Consumers\Messages\WorkerConsumerMessage;
use Micromus\KafkaBus\Consumers\Pipelines\ConsumerPipelineHandler;
use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerException;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerStreamInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Handlers\MessageHandlerInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Pipelines\ConsumerPipelineMiddlewareInterface;
use Micromus\KafkaBus\Pipelines\Pipeline;
use Micromus\KafkaBus\Pipelines\PipelineBuilder;
use Micromus\KafkaBus\Testing\Exceptions\KafkaMessagesEndedException;

class ConsumerStream implements ConsumerStreamInterface
{
    protected bool $forceStop = false;

    private const IGNORABLE_CONSUMER_ERRORS = [
        RD_KAFKA_RESP_ERR__PARTITION_EOF,
        RD_KAFKA_RESP_ERR__TRANSPORT,
        RD_KAFKA_RESP_ERR_REQUEST_TIMED_OUT,
        RD_KAFKA_RESP_ERR__TIMED_OUT,
    ];

    /**
     * @param ConsumerInterface $consumer
     * @param MessageHandlerInterface $messageHandler
     * @param string $workerName
     * @param list<ConsumerPipelineMiddlewareInterface> $middleware
     */
    public function __construct(
        protected ConsumerInterface $consumer,
        protected MessageHandlerInterface $messageHandler,
        protected string $workerName,
        protected array $middleware = []
    ) {
    }

    public function listen(): void
    {
        do {
            try {
                $consumerMessage = $this->consumer
                    ->getMessage();

                $workerMessage = new WorkerConsumerMessage($this->workerName, $consumerMessage);

                $pipeline = PipelineBuilder::for(new ConsumerPipelineHandler($workerMessage, $this->messageHandler))
                    ->middleware($this->middleware)
                    ->create();

                $pipeline->start();

                $this->consumer->commit($workerMessage);
            }
            catch (MessageConsumerException $exception) {
                if (! in_array($exception->consumerMessage->err, self::IGNORABLE_CONSUMER_ERRORS, true)) {
                    throw $exception;
                }
            }
            catch (KafkaMessagesEndedException) {
                return;
            }
        }
        while (! $this->forceStop);
    }

    public function forceStop(): void
    {
        $this->forceStop = true;
    }
}
