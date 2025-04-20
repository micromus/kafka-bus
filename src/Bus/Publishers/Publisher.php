<?php

namespace Micromus\KafkaBus\Bus\Publishers;

use Micromus\KafkaBus\Bus\MessageBatch;
use Micromus\KafkaBus\Bus\Publishers\Router\PublisherRouter;
use Micromus\KafkaBus\Bus\Publishers\Router\Route;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;

class Publisher
{
    public function __construct(
        protected PublisherRouter $router
    ) {
    }

    /**
     * @return list<Route>
     */
    public function routes(): array
    {
        return $this->router
            ->routes();
    }

    /**
     * @template TMessage of ProducerMessageInterface
     *
     * @param MessageBatch<TMessage> $messageBatch
     * @return void
     */
    public function publish(MessageBatch $messageBatch): void
    {
        $this->router
            ->publish($messageBatch);
    }
}
