<?php

namespace Micromus\KafkaBus\Producers\Router;

use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Producers\ProducerStream;
use Micromus\KafkaBus\Contracts\Producers\ProducerStreamFactory;
use Micromus\KafkaBus\Exceptions\Producers\RouteProducerException;

class ProducerRouter
{
    protected array $activeProducerStreams = [];

    public function __construct(
        protected Connection $connection,
        protected ProducerStreamFactory $producerStreamFactory,
        protected ProducerRoutes $routes
    ) {
    }

    /**
     * @throws RouteProducerException
     */
    public function publish(array $messages): void
    {
        $groupMessages = $this->groupMessagesByClass($messages);

        foreach ($groupMessages as $messageClass => $messages) {
            $this->getOrCreateProducerStream($messageClass)
                ->handle($messages);
        }
    }

    private function groupMessagesByClass(array $messages): array
    {
        $result = [];

        foreach ($messages as $message) {
            $result[get_class($message)][] = $message;
        }

        return $result;
    }

    /**
     * @throws RouteProducerException
     */
    private function getOrCreateProducerStream(string $messageClass): ProducerStream
    {
        if (! isset($this->activeProducerStreams[$messageClass])) {
            $this->activeProducerStreams[$messageClass] = $this->createProducerStream($messageClass);
        }

        return $this->activeProducerStreams[$messageClass];
    }

    /**
     * @throws RouteProducerException
     */
    private function createProducerStream(string $messageClass): ProducerStream
    {
        $route = $this->routes->get($messageClass)
            ?? throw new RouteProducerException("Route for message [$messageClass] not found");

        return $this->producerStreamFactory
            ->create($this->connection, $route->topicKey, $route->options);
    }
}
