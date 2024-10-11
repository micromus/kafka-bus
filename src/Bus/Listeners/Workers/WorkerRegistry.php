<?php

namespace Micromus\KafkaBus\Bus\Listeners\Workers;

class WorkerRegistry
{
    /**
     * @var array<string, Worker>
     */
    protected array $workers = [];

    public function add(string $workerName, Worker $worker): self
    {
        $this->workers[$workerName] = $worker;

        return $this;
    }

    public function get(string $workerName): ?Worker
    {
        return $this->workers[$workerName] ?? null;
    }
}
