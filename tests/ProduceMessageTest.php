<?php

use Micromus\KafkaBus\Bus;
use Micromus\KafkaBus\Bus\Publishers\Router\PublisherRoutes;
use Micromus\KafkaBus\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessageHandlerFactory;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouterFactory;
use Micromus\KafkaBus\Pipelines\PipelineFactory;
use Micromus\KafkaBus\Producers\ProducerStreamFactory;
use Micromus\KafkaBus\Support\Resolvers\NativeResolver;
use Micromus\KafkaBus\Testing\Connections\ConnectionFaker;
use Micromus\KafkaBus\Testing\Connections\ConnectionRegistryFaker;
use Micromus\KafkaBus\Testing\Messages\ProducerMessageFaker;
use Micromus\KafkaBus\Topics\Topic;
use Micromus\KafkaBus\Topics\TopicRegistry;
use Testo\Assert;
use Testo\Test;

#[Test]
function can_produce_message(): void
{
    $topicRegistry = (new TopicRegistry())
        ->add(new Topic('production.fact.products.1', 'products'));

    $connectionFaker = new ConnectionFaker();

    $routes = (new PublisherRoutes())
        ->add(new Bus\Publishers\Router\Route(ProducerMessageFaker::class, 'products'));

    $bus = new Bus(
        new Bus\ThreadRegistry(
            new ConnectionRegistryFaker($connectionFaker),
            new Bus\Publishers\PublisherFactory(
                new ProducerStreamFactory(new PipelineFactory(new NativeResolver())),
                $topicRegistry,
                $routes
            ),
            new Bus\Listeners\ListenerFactory(
                new ConsumerStreamFactory(
                    new ConsumerMessageHandlerFactory(
                        new PipelineFactory(new NativeResolver()),
                        new ConsumerRouterFactory(
                            new NativeResolver(),
                            new PipelineFactory(new NativeResolver()),
                            $topicRegistry
                        )
                    )
                )
            )
        ),
        'default'
    );

    $bus->publish([new ProducerMessageFaker('test-message', ['foo' => 'bar'], 5)]);

    Assert::array($connectionFaker->publishedMessages)
        ->hasCount(1)
        ->hasKeys('production.fact.products.1');

    $message = $connectionFaker->publishedMessages['production.fact.products.1'][0];

    Assert::true($message->payload == 'test-message');
    Assert::true($message->partition == 5);
    Assert::true($message->headers == ['foo' => 'bar']);
}
