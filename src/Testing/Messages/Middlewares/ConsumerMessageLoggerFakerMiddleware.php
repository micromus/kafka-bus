<?php

namespace Micromus\KafkaBus\Testing\Messages\Middlewares;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Pipelines\Messages\MessagePipelineHandlerInterface;
use Micromus\KafkaBus\Interfaces\Consumers\Pipelines\Messages\MessagePipelineMiddlewareInterface;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineInterface;

final class ConsumerMessageLoggerFakerMiddleware implements MessagePipelineMiddlewareInterface
{
    /**
     * @param PipelineInterface<MessagePipelineHandlerInterface> $pipeline
     * @return PipelineInterface<MessagePipelineHandlerInterface>
     */
    public function handle(PipelineInterface $pipeline): PipelineInterface
    {
        $message = $pipeline->handler()
            ->target();

        \assert($message instanceof ConsumerMessageInterface);

        echo "Execute consumer message: {$message->msgId()}" . PHP_EOL;

        $pipeline->continue();

        echo "Consumer message executed." . PHP_EOL;

        return $pipeline;
    }
}
