<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Support;

use Psr\Container\ContainerInterface;

final class NativeContainer implements ContainerInterface
{
    /**
     * @var array<class-string, mixed>
     */
    protected array $singletons = [];

    public function set(object $object): void
    {
        $this->singletons[\get_class($object)] = $object;
    }

    /**
     * @template TObject
     * @param class-string<TObject> $id
     * @return TObject
     */
    public function get(string $id)
    {
        if (\array_key_exists($id, $this->singletons)) {
            return $this->singletons[$id];
        }

        return new $id();
    }

    /**
     * @param class-string $id
     * @return bool
     */
    public function has(string $id): bool
    {
        return class_exists($id);
    }
}
