<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Consumers\Router\Extractors\MessageFactoryClassExtractor;
use Micromus\KafkaBus\Consumers\Router\Extractors\MiddlewareClassExtractor;
use Micromus\KafkaBus\Exceptions\Consumers\RouteConsumerException;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineFactoryInterface;
use Micromus\KafkaBus\Interfaces\ResolverInterface;
use ReflectionException;

class ConsumerRouter
{
    protected array $executors = [];

    protected MessageFactoryClassExtractor $messageFactoryClassExtractor;

    protected MiddlewareClassExtractor $middlewareClassExtractor;

    public function __construct(
        protected ResolverInterface $resolver,
        protected PipelineFactoryInterface $pipelineFactory,
        protected ConsumerRoutes $routes
    ) {
        $this->messageFactoryClassExtractor = new MessageFactoryClassExtractor();
        $this->middlewareClassExtractor = new MiddlewareClassExtractor();
    }

    public function topics(): array
    {
        return $this->routes->topics();
    }

    /**
     * @param ConsumerMessageInterface $consumerMessage
     * @return void
     *
     * @throws ReflectionException
     */
    public function handle(ConsumerMessageInterface $consumerMessage): void
    {
        $executor = $this->getOrCreateExecutor($consumerMessage->topicName());
        $executor->execute($consumerMessage);
    }

    /**
     * @param string $topicName
     * @return Executor
     *
     * @throws ReflectionException
     */
    private function getOrCreateExecutor(string $topicName): Executor
    {
        if (! isset($this->executors[$topicName])) {
            $this->executors[$topicName] = $this->makeExecutor($topicName);
        }

        return $this->executors[$topicName];
    }

    /**
     * @param string $topicName
     * @return Executor
     *
     * @throws ReflectionException
     */
    protected function makeExecutor(string $topicName): Executor
    {
        $route = $this->routes->get($topicName)
            ?? throw new RouteConsumerException("Route for topic [$topicName] not found.");

        $handler = $this->resolver->resolve($route->handlerClass);
        $messageFactoryClass = $this->messageFactoryClassExtractor->extract($handler);
        $middlewares = $this->middlewareClassExtractor->extract($handler);

        return new Executor(
            $handler,
            $this->pipelineFactory->create($middlewares),
            $this->resolver->resolve($messageFactoryClass)
        );
    }
}
