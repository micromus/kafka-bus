<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Consumers\Pipelines;

use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerNotHandledException;
use Micromus\KafkaBus\Interfaces\Consumers\Handlers\MessageHandlerInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\WorkerConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Pipelines\ConsumerPipelineHandlerInterface;

final class ConsumerPipelineHandler implements ConsumerPipelineHandlerInterface
{
    public function __construct(
        protected WorkerConsumerMessageInterface $target,
        protected MessageHandlerInterface $messageHandler
    ) {
    }

    public function target(): WorkerConsumerMessageInterface
    {
        return $this->target;
    }

    /**
     * @throws MessageConsumerNotHandledException
     */
    public function handle(): WorkerConsumerMessageInterface
    {
        $this->messageHandler
            ->handle($this->target);

        return $this->target;
    }
}
