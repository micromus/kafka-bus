<?php

namespace Micromus\KafkaBus\Bus\Listeners;

class ListenerRegistry
{
    protected array $listeners = [];

    public function add(string $listenerName, Options $options = new Options): self
    {
        $this->listeners[$listenerName] = $options;

        return $this;
    }

    public function get(string $listenerName): ?Options
    {
        return $this->listeners[$listenerName] ?? null;
    }
}
