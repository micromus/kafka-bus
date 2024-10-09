<?php

namespace Micromus\KafkaBus\Bus\Listeners\Workers;

class WorkerRegistry
{
    /**
     * @var array<string, Worker>
     */
    protected array $groups = [];

    public function add(string $groupName, Worker $group): self
    {
        $this->groups[$groupName] = $group;

        return $this;
    }

    public function get(string $groupName): ?Worker
    {
        return $this->groups[$groupName] ?? null;
    }
}
