<?php

namespace Micromus\KafkaBus\Producers;

use Micromus\KafkaBus\Bus\Publishers\Router\Route;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerStreamInterface;
use Micromus\KafkaBus\Pipelines\PipelineBuilder;
use Micromus\KafkaBus\Producers\Messages\ProducerMessage;
use Micromus\KafkaBus\Producers\Pipelines\ProducerPipelineHandler;

/**
 * @template TMessage of ProducerMessageInterface
 * @implements ProducerStreamInterface<TMessage>
 */
class ProducerStream implements ProducerStreamInterface
{
    /**
     * @param Route<TMessage> $route
     * @param ProducerInterface $producer
     */
    public function __construct(
        protected Route $route,
        protected ProducerInterface $producer,
    ) {
    }

    public function handle(iterable $messages): void
    {
        $this->producer
            ->produce($this->prepareMessages($messages));
    }

    /**
     * @param iterable<ProducerMessageInterface> $messages
     * @return iterable<ProducerMessage>
     */
    private function prepareMessages(iterable $messages): iterable
    {
        foreach ($messages as $message) {
            $producerHandler = new ProducerPipelineHandler($message, $this->route->topic);
            $producerMessage = PipelineBuilder::for($producerHandler)
                ->middleware($this->route->options->middlewares)
                ->create()
                ->start();

            if (!\is_null($producerMessage)) {
                yield $producerMessage;
            }
        }
    }
}
