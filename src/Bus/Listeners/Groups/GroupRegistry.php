<?php

namespace Micromus\KafkaBus\Bus\Listeners\Groups;

class GroupRegistry
{
    /**
     * @var array<string, Group>
     */
    protected array $groups = [];

    public function add(string $groupName, Group $group): self
    {
        $this->groups[$groupName] = $group;

        return $this;
    }

    public function get(string $groupName): ?Group
    {
        return $this->groups[$groupName] ?? null;
    }
}
