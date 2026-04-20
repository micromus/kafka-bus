<?php

namespace Micromus\KafkaBus\Bus\Listeners\Workers;

use Micromus\KafkaBus\Interfaces\Bus\Listeners\WorkerRegistryInterface;

final class MemoryWorkerRegistry implements WorkerRegistryInterface
{
    /**
     * @var array<string, Worker>
     */
    protected array $workers = [];

    public function add(Worker $worker): self
    {
        $this->workers[$worker->name] = $worker;

        return $this;
    }

    public function get(string $workerName): ?Worker
    {
        return $this->workers[$workerName] ?? null;
    }

    public static function make(): self
    {
        return new self();
    }
}
