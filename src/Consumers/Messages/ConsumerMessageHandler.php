<?php

namespace Micromus\KafkaBus\Consumers\Messages;

use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouter;
use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerNotHandledException;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageHandlerInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\WorkerConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineInterface;
use Throwable;

class ConsumerMessageHandler implements ConsumerMessageHandlerInterface
{
    public function __construct(
        protected string $workerName,
        protected ConsumerRouter $consumerRouter,
        protected PipelineInterface $pipeline,
    ) {
    }

    public function topics(): array
    {
        return $this->consumerRouter->topics();
    }

    public function handle(ConsumerMessageInterface $message): void
    {
        $this->pipeline
            ->then(new WorkerConsumerMessage($this->workerName, $message), $this->handleMessage(...));
    }

    /**
     * @throws MessageConsumerNotHandledException
     */
    protected function handleMessage(WorkerConsumerMessageInterface $message): ConsumerMessageInterface
    {
        try {
            $this->consumerRouter
                ->handle($message);

            return $message;
        }
        catch (Throwable $exception) {
            throw new MessageConsumerNotHandledException($message, $exception);
        }
    }
}
