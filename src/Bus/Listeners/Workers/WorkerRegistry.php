<?php

namespace Micromus\KafkaBus\Bus\Listeners\Workers;

class WorkerRegistry
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
}
