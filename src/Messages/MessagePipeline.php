<?php

namespace Micromus\KafkaBus\Messages;

use Closure;
use Micromus\KafkaBus\Interfaces\Messages\MessagePipelineInterface;

class MessagePipeline implements MessagePipelineInterface
{
    public function __construct(
        protected array $pipes = []
    ) {
    }

    public function then(mixed $message, Closure $destination): mixed
    {
        $pipeline = array_reduce(
            array_reverse($this->pipes),
            $this->carry(),
            $this->prepareDestination($destination),
        );

        return $pipeline($message);
    }

    protected function carry(): Closure
    {
        return function ($stack, $pipe) {
            return function ($message) use ($stack, $pipe) {
                return method_exists($pipe, 'handle')
                    ? $pipe->handle($message, $stack)
                    : $pipe($message, $stack);
            };
        };
    }

    private function prepareDestination(Closure $destination): Closure
    {
        return function ($message) use ($destination) {
            return $destination($message);
        };
    }
}
