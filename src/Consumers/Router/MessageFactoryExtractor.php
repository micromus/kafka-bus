<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Closure;
use Micromus\KafkaBus\Consumers\Attributes\MessageFactory;
use Micromus\KafkaBus\Consumers\Messages\JsonMessageFactory;
use Micromus\KafkaBus\Consumers\Messages\OriginalMessageFactory;
use Micromus\KafkaBus\Consumers\Messages\StringMessageFactory;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\MessageFactoryInterface;
use RdKafka\Message;

class MessageFactoryExtractor
{
    public function extract(callable $handler): ?MessageFactoryInterface
    {
        $reflection = match (true) {
            $handler instanceof Closure => new \ReflectionFunction($handler),
            \is_object($handler) && method_exists($handler, '__invoke') => new \ReflectionMethod($handler, '__invoke'),
            default => null
        };

        if ($reflection === null) {
            return null;
        }

        return $this->fromAttribute($reflection)
            ?? $this->fromArgs($reflection);
    }

    private function fromAttribute(\ReflectionFunctionAbstract $reflection): ?MessageFactoryInterface
    {
        /** @var \ReflectionAttribute<MessageFactory>|null $attribute */
        $attribute = $reflection->getAttributes(MessageFactory::class)[0] ?? null;

        return $attribute?->newInstance()
            ->messageFactory;
    }

    private function fromArgs(\ReflectionFunctionAbstract $reflection): ?MessageFactoryInterface
    {
        $arg = $reflection->getParameters()[0] ?? null;
        $type = $arg?->getType() ?? null;

        if (!$type instanceof \ReflectionNamedType) {
            return null;
        }

        return match (true) {
            $type->getName() == 'string' => new StringMessageFactory(),
            $type->getName() == 'array' => new JsonMessageFactory(),
            $type->getName() == Message::class => new OriginalMessageFactory(),
            default => null,
        };
    }
}
