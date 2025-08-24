<?php

namespace Micromus\KafkaBus\Consumers\Handlers;

use Micromus\KafkaBus\Consumers\Router\ConsumerRouter;
use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerNotHandledException;
use Micromus\KafkaBus\Interfaces\Consumers\Handlers\MessageHandlerInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\WorkerConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Pipelines\Messages\MessagePipelineMiddlewareInterface;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineInterface;
use Throwable;

class MessageHandler implements MessageHandlerInterface
{
    /**
     * @param ConsumerRouter $consumerRouter
     * @param list<MessagePipelineMiddlewareInterface> $middlewares
     */
    public function __construct(
        protected ConsumerRouter $consumerRouter,
        protected array $middlewares = [],
    ) {
    }

    public function topics(): array
    {
        return $this->consumerRouter->topics();
    }

    public function handle(WorkerConsumerMessageInterface $message): void
    {
        try {
            $this->consumerRouter
                ->handle($message);
        }
        catch (Throwable $exception) {
            throw new MessageConsumerNotHandledException($message, $exception);
        }
    }
}
