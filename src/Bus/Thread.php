<?php

namespace Micromus\KafkaBus\Bus;

use Micromus\KafkaBus\Bus\Listeners\Listener;
use Micromus\KafkaBus\Bus\Listeners\ListenerFactory;
use Micromus\KafkaBus\Bus\Publishers\Publisher;
use Micromus\KafkaBus\Bus\Publishers\PublisherFactory;
use Micromus\KafkaBus\Interfaces\Bus\ThreadInterface;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;

final class Thread implements ThreadInterface
{
    protected Publisher $publisher;

    public function __construct(
        protected ConnectionInterface $connection,
        protected ListenerFactory $listenerFactory,
        PublisherFactory $publisherFactory,
    ) {
        $this->publisher = $publisherFactory->create($this->connection);
    }

    public function routes(): array
    {
        return $this->publisher
            ->routes();
    }

    public function publish(ProducerMessageInterface $message): void
    {
        $this->publishBatch(MessageBatch::fromArray([$message]));
    }

    public function publishBatch(MessageBatch $messageBatch): void
    {
        $this->publisher
            ->publish($messageBatch);
    }

    public function listener(string $listenerWorkerName): Listener
    {
        return $this->listenerFactory
            ->create($this->connection, $listenerWorkerName);
    }
}
