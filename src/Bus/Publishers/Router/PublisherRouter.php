<?php

namespace Micromus\KafkaBus\Bus\Publishers\Router;

use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerStreamInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerStreamFactoryInterface;
use Micromus\KafkaBus\Exceptions\Producers\RouteProducerException;
use Micromus\KafkaBus\Topics\TopicRegistry;

class PublisherRouter
{
    protected array $activeProducerStreams = [];

    public function __construct(
        protected ConnectionInterface            $connection,
        protected ProducerStreamFactoryInterface $producerStreamFactory,
        protected TopicRegistry                  $topicRegistry,
        protected PublisherRoutes                $routes
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
    private function getOrCreateProducerStream(string $messageClass): ProducerStreamInterface
    {
        if (! isset($this->activeProducerStreams[$messageClass])) {
            $this->activeProducerStreams[$messageClass] = $this->createProducerStream($messageClass);
        }

        return $this->activeProducerStreams[$messageClass];
    }

    /**
     * @throws RouteProducerException
     */
    private function createProducerStream(string $messageClass): ProducerStreamInterface
    {
        $route = $this->routes->get($messageClass)
            ?? throw new RouteProducerException("Route for message [$messageClass] not found");

        return $this->producerStreamFactory
            ->create($this->connection, $this->topicRegistry->get($route->topicKey), $route->options);
    }
}
