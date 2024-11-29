<?php

use Micromus\KafkaBus\Bus;
use Micromus\KafkaBus\Bus\Publishers\Router\PublisherRoutes;
use Micromus\KafkaBus\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessageHandlerFactory;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouterFactory;
use Micromus\KafkaBus\Messages\MessagePipelineFactory;
use Micromus\KafkaBus\Producers\ProducerStreamFactory;
use Micromus\KafkaBus\Support\Resolvers\NativeResolver;
use Micromus\KafkaBus\Testing\Connections\ConnectionFaker;
use Micromus\KafkaBus\Testing\Connections\ConnectionRegistryFaker;
use Micromus\KafkaBus\Testing\Messages\ProducerMessageFaker;
use Micromus\KafkaBus\Topics\Topic;
use Micromus\KafkaBus\Topics\TopicRegistry;

it('can produce message', function () {
    $topicRegistry = (new TopicRegistry())
        ->add(new Topic('production.fact.products.1', 'products'));

    $connectionFaker = new ConnectionFaker($topicRegistry);

    $routes = (new PublisherRoutes())
        ->add(new Bus\Publishers\Router\Route(ProducerMessageFaker::class, 'products'));

    $bus = new Bus(
        new Bus\ThreadRegistry(
            new ConnectionRegistryFaker($connectionFaker),
            new Bus\Publishers\PublisherFactory(
                new ProducerStreamFactory(new MessagePipelineFactory(new NativeResolver())),
                $topicRegistry,
                $routes
            ),
            new Bus\Listeners\ListenerFactory(
                new ConsumerStreamFactory(
                    new ConsumerMessageHandlerFactory(
                        new MessagePipelineFactory(new NativeResolver()),
                        new ConsumerRouterFactory(new NativeResolver(), $topicRegistry)
                    )
                )
            )
        ),
        'default'
    );

    $bus->publish(new ProducerMessageFaker('test-message', ['foo' => 'bar'], 5));

    expect($connectionFaker->publishedMessages)
        ->toHaveCount(1)
        ->and($connectionFaker->publishedMessages['production.fact.products.1'][0])
        ->toHaveProperties([
            'payload' => 'test-message',
            'headers' => ['foo' => 'bar'],
            'partition' => 5,
        ]);
});
