<?php

namespace Micromus\KafkaBus\Bus;

use Micromus\KafkaBus\Bus\Listeners\Listener;
use Micromus\KafkaBus\Bus\Listeners\ListenerFactory;
use Micromus\KafkaBus\Bus\Publishers\Publisher;
use Micromus\KafkaBus\Bus\Publishers\PublisherFactory;
use Micromus\KafkaBus\Interfaces\Bus\ThreadInterface;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;

class Thread implements ThreadInterface
{
    protected ?Publisher $publisher = null;

    public function __construct(
        protected ConnectionInterface $connection,
        protected ListenerFactory $listenerFactory,
        protected PublisherFactory $publisherFactory,
    ) {
    }

    private function getPublisher(): Publisher
    {
        if ($this->publisher === null) {
            $this->publisher = $this->publisherFactory
                ->create($this->connection);
        }

        return $this->publisher;
    }

    public function publish(iterable $messages): void
    {
        $this->getPublisher()
            ->publish($messages);
    }

    public function createListener(string $listenerWorkerName): Listener
    {
        return $this->listenerFactory
            ->create($this->connection, $listenerWorkerName);
    }
}
