<?php

namespace Micromus\KafkaBus\Consumers\Messages;

use Micromus\KafkaBus\Consumers\Router\ConsumerRouter;
use Micromus\KafkaBus\Exceptions\Consumers\MessageConsumerNotHandledException;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageHandlerInterface;
use Micromus\KafkaBus\Interfaces\Messages\MessagePipelineInterface;
use Throwable;

class ConsumerMessageHandler implements ConsumerMessageHandlerInterface
{
    public function __construct(
        protected ConsumerRouter $consumerRouter,
        protected MessagePipelineInterface $messagePipeline,
    ) {
    }

    public function topics(): array
    {
        return $this->consumerRouter->topics();
    }

    public function handle(ConsumerMessage $message): void
    {
        try {
            $this->messagePipeline
                ->then($message, function (ConsumerMessage $message) {
                    $this->consumerRouter
                        ->handle($message);

                    return $message;
                });
        }
        catch (Throwable $exception) {
            throw new MessageConsumerNotHandledException($message, $exception);
        }
    }
}
