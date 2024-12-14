<?php

namespace Micromus\KafkaBus\Bus\Publishers\Router;

use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerStreamInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerStreamFactoryInterface;
use Micromus\KafkaBus\Exceptions\Producers\RouteProducerException;
use Micromus\KafkaBus\Topics\TopicRegistry;
use WeakMap;

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
     * @param ProducerMessageInterface[] $messages
     * @return void
     *
     * @throws RouteProducerException
     */
    public function publish(iterable $messages): void
    {
        $producerStreamCollection = $this->makeProducerStreamCollection($messages);

        foreach ($producerStreamCollection as $producerStreamItem) {
            $producerStream = $producerStreamItem['producer_stream'];
            $producerStream->handle($producerStreamItem['messages']);
        }
    }

    /**
     * @param ProducerMessageInterface[] $messages
     * @return array<int, array{producer_stream: ProducerStreamInterface, messages: ProducerMessageInterface[]}>
     */
    private function makeProducerStreamCollection(iterable $messages): array
    {
        $result = [];

        foreach ($messages as $message) {
            $result[get_class($message)][] = $message;
        }

        $producerStreamCollection = [];

        // Создание ProducerStream происходит на этом этапе чтобы
        // если вдруг вылетит RouteProducerException
        // то не отправлять все сообщения
        foreach ($result as $messageClass => $messages) {
            $producerStreamCollection[] = [
                'producer_stream' => $this->getOrCreateProducerStream($messageClass),
                'messages' => $messages,
            ];
        }

        return $producerStreamCollection;
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
