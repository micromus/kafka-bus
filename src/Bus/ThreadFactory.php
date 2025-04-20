<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Bus;

use Micromus\KafkaBus\Bus\Listeners\ListenerFactory;
use Micromus\KafkaBus\Bus\Publishers\PublisherFactory;
use Micromus\KafkaBus\Interfaces\Bus\ThreadInterface;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;

final class ThreadFactory
{
    public function __construct(
        protected ListenerFactory $listenerFactory,
        protected PublisherFactory $publisherFactory,
    ) {
    }

    public function create(ConnectionInterface $connection): ThreadInterface
    {
        return new Thread($connection, $this->listenerFactory, $this->publisherFactory);
    }
}
