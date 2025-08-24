<?php

namespace Micromus\KafkaBus\Interfaces\Bus;

use Micromus\KafkaBus\Bus\Listeners\Listener;
use Micromus\KafkaBus\Bus\MessageBatch;
use Micromus\KafkaBus\Bus\Publishers\Router\Route;
use Micromus\KafkaBus\Exceptions\Listeners\ListenerException;
use Micromus\KafkaBus\Exceptions\Producers\RouteProducerException;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;

interface ThreadInterface
{
    /**
     * @return list<Route>
     */
    public function routes(): array;

    /**
     * @param ProducerMessageInterface $message
     * @return void
     *
     * @throws RouteProducerException
     */
    public function publish(ProducerMessageInterface $message): void;

    /**
     * @template TMessage of ProducerMessageInterface
     * @param MessageBatch<TMessage> $messageBatch
     * @return void
     *
     * @throws RouteProducerException
     */
    public function publishBatch(MessageBatch $messageBatch): void;

    /**
     * @param non-empty-string $listenerWorkerName
     * @return Listener
     *
     * @throws ListenerException
     */
    public function listener(string $listenerWorkerName): Listener;
}
