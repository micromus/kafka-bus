<?php

namespace Micromus\KafkaBus\Consumers\Router;

use Micromus\KafkaBus\Consumers\Router\Extractors\MessageFactoryClassExtractor;
use Micromus\KafkaBus\Consumers\Router\Extractors\MiddlewareClassExtractor;
use Micromus\KafkaBus\Exceptions\Consumers\RouteConsumerException;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Interfaces\Pipelines\PipelineFactoryInterface;
use Micromus\KafkaBus\Topics\Topic;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class ConsumerRouter
{
    protected array $executors = [];

    protected MessageFactoryClassExtractor $messageFactoryClassExtractor;

    protected MiddlewareClassExtractor $middlewareClassExtractor;

    public function __construct(
        protected ContainerInterface $container,
        protected PipelineFactoryInterface $pipelineFactory,
        protected ConsumerRoutes $routes
    ) {
        $this->messageFactoryClassExtractor = new MessageFactoryClassExtractor();
        $this->middlewareClassExtractor = new MiddlewareClassExtractor();
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
     *
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
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
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    protected function makeExecutor(string $topicName): Executor
    {
        $route = $this->routes->get($topicName)
            ?? throw new RouteConsumerException("Route for topic [$topicName] not found.");

        $handler = $this->container->get($route->handlerClass);
        $messageFactoryClass = $this->messageFactoryClassExtractor->extract($handler);
        $middlewares = $this->middlewareClassExtractor->extract($handler);

        return new Executor(
            $handler,
            $this->pipelineFactory->create($middlewares),
            $this->container->get($messageFactoryClass)
        );
    }
}
