<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Exceptions\Consumers\RouteConsumerException;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Topics\Topic;

final class ConsumerRouter
{
    /**
     * @var array<string, RouteExecutor>
     */
    protected array $executors = [];

    public function __construct(
        protected ConsumerRoutes $routes
    ) {
    }

    /**
     * @return Topic[]
     */
    public function topics(): array
    {
        return $this->routes->topics();
    }

    /**
     * @param ConsumerMessageInterface $consumerMessage
     * @return void
     */
    public function handle(ConsumerMessageInterface $consumerMessage): void
    {
        $executor = $this->getOrCreateExecutor($consumerMessage->topicName());
        $executor->execute($consumerMessage);
    }

    /**
     * @param string $topicName
     * @return RouteExecutor
     */
    private function getOrCreateExecutor(string $topicName): RouteExecutor
    {
        if (! isset($this->executors[$topicName])) {
            $this->executors[$topicName] = $this->makeExecutor($topicName);
        }

        return $this->executors[$topicName];
    }

    /**
     * @param string $topicName
     * @return RouteExecutor
     */
    protected function makeExecutor(string $topicName): RouteExecutor
    {
        $route = $this->routes->get($topicName)
            ?? throw new RouteConsumerException("Route for topic [$topicName] not found.");

        return new RouteExecutor(
            $route->handler,
            $route->messageFactory,
            $route->middleware
        );
    }
}
