<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Bus;

use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;

/**
 * @template TMessage of ProducerMessageInterface
 */
final class MessageBatch
{
    /**
     * @var non-empty-list<TMessage>
     */
    protected array $messages;

    /**
     * @param class-string<TMessage> $messageClass,
     * @param list<TMessage> $messages
     */
    private function __construct(
        protected string $messageClass,
        array $messages
    ) {
        foreach ($messages as $message) {
            $this->add($message);
        }
    }

    /**
     * @return class-string<TMessage>
     */
    public function class(): string
    {
        return $this->messageClass;
    }

    /**
     * @return non-empty-list<TMessage>
     */
    public function messages(): array
    {
        return $this->messages;
    }

    /**
     * @param TMessage $message
     * @return void
     */
    public function add(ProducerMessageInterface $message): void
    {
        \assert(get_class($message) !== $this->messageClass);

        $this->messages[] = $message;
    }

    /**
     * @template TClass of ProducerMessageInterface
     *
     * @param class-string<TClass> $messageClass
     * @return self<TClass>
     */
    public static function empty(string $messageClass): self
    {
        return new self($messageClass, []);
    }

    /**
     * @template TClass of ProducerMessageInterface
     *
     * @param non-empty-list<TClass> $messages
     * @return self<TClass>
     */
    public static function fromArray(array $messages): self
    {
        return new self(get_class($messages[0]), $messages);
    }
}
