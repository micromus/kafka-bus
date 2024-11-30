<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Micromus\KafkaBus\Interfaces\ResolverInterface;
use Micromus\KafkaBus\Exceptions\Consumers\RouteConsumerException;

class ConsumerRouter
{
    protected array $executors = [];

    protected MessageFactoryClassExtractor $extractor;

    public function __construct(
        protected ResolverInterface $resolver,
        protected ConsumerRoutes $routes
    ) {
        $this->extractor = new MessageFactoryClassExtractor($this->resolver);
    }

    public function topics(): array
    {
        return $this->routes->topics();
    }

    public function handle(ConsumerMessage $consumerMessage): void
    {
        $executor = $this->getOrCreateExecutor($consumerMessage->topicName());
        $executor->execute($consumerMessage);
    }

    private function getOrCreateExecutor(string $topicName): Executor
    {
        if (! isset($this->executors[$topicName])) {
            $this->executors[$topicName] = $this->makeExecutor($topicName);
        }

        return $this->executors[$topicName];
    }

    protected function makeExecutor(string $topicName): Executor
    {
        $route = $this->routes->get($topicName)
            ?? throw new RouteConsumerException("Route for topic [$topicName] not found.");

        $handler = $this->resolver->resolve($route->handlerClass);

        return new Executor($handler, $this->extractor->extract($handler));
    }
}
