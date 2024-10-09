<?php

namespace Micromus\KafkaBus\Bus;

use Micromus\KafkaBus\Bus\Listeners\ListenerFactory;
use Micromus\KafkaBus\Bus\Publishers\Publisher;
use Micromus\KafkaBus\Bus\Publishers\PublisherFactory;
use Micromus\KafkaBus\Contracts\Bus\Thread as ThreadContract;
use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Messages\Message;

class Thread implements ThreadContract
{
    protected ?Publisher $publisher = null;

    public function __construct(
        protected Connection $connection,
        protected ListenerFactory $listenerFactory,
        protected PublisherFactory $publisherFactory,
    ) {}

    private function getPublisher(): Publisher
    {
        if ($this->publisher === null) {
            $this->publisher = $this->publisherFactory
                ->create($this->connection);
        }

        return $this->publisher;
    }

    public function publish(Message $message): void
    {
        $this->publishMany([$message]);
    }

    public function publishMany(array $messages): void
    {
        $this->getPublisher()
            ->publish($messages);
    }

    public function listen(string $listenerWorkerName): void
    {
        $this->listenerFactory
            ->create($this->connection, $listenerWorkerName)
            ->listen();
    }
}
