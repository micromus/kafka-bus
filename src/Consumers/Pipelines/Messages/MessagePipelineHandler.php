<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Consumers\Pipelines\Messages;

use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Interfaces\Consumers\Pipelines\Messages\MessagePipelineHandlerInterface;

final class MessagePipelineHandler implements MessagePipelineHandlerInterface
{
    /**
     * @param callable $handler
     * @param mixed $target
     */
    public function __construct(
        protected mixed $handler,
        protected mixed $target,
    ) {
    }

    public function target(): mixed
    {
        return $this->target;
    }

    /**
     * @return true
     */
    public function handle(): true
    {
        call_user_func($this->handler, $this->target);

        return true;
    }
}
