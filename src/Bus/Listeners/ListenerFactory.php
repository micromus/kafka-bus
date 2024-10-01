<?php

namespace Micromus\KafkaBus\Bus\Listeners;

use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Exceptions\Consumers\ListenerException;

class ListenerFactory
{
    public function __construct(
        protected ConsumerStreamFactory $streamFactory,
        protected ListenerRegistry $registry = new ListenerRegistry,
    ) {}

    public function create(Connection $connection, string $listenerName): Listener
    {
        $listenerOptions = $this->getListenerOptions($listenerName);

        return new Listener(
            $connection,
            $this->streamFactory,
            $listenerOptions
        );
    }

    private function getListenerOptions(string $listenerName): Options
    {
        return $this->registry->get($listenerName)
            ?: throw new ListenerException("Listener [$listenerName] not found.");
    }
}
