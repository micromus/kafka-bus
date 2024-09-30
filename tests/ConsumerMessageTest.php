<?php

use Micromus\KafkaBus\Bus;
use Micromus\KafkaBus\Bus\Listeners\ListenerFactory;
use Micromus\KafkaBus\Bus\Publishers\Router\ProducerRouterFactory;
use Micromus\KafkaBus\Bus\Publishers\Router\PublisherRoutes;
use Micromus\KafkaBus\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouterFactory;
use Micromus\KafkaBus\Messages\MessagePipelineFactory;
use Micromus\KafkaBus\Producers\ProducerStreamFactory;
use Micromus\KafkaBus\Support\Resolvers\NativeResolver;
use Micromus\KafkaBus\Support\TopicNameResolver;
use Micromus\KafkaBus\Testing\ConnectionFaker;
use Micromus\KafkaBus\Testing\ConnectionRegistryFaker;
use Micromus\KafkaBus\Testing\ConsumerHandlerFaker;
use RdKafka\Message;

test('can consume message', function () {
    $topicNameResolver = new TopicNameResolver('production.', ['products' => 'fact.products.1']);
    $connectionFaker = new ConnectionFaker($topicNameResolver);

    $message = new Message();
    $message->payload = 'test-message';
    $message->headers = ['foo' => 'bar'];

    $connectionFaker->addMessage('products', $message);

    $listenerOptions = new Bus\Listeners\Options(
        (new Bus\Listeners\Router\Routes())
            ->add(new Bus\Listeners\Router\Route('products', ConsumerHandlerFaker::class))
    );

    $listenerRegistry = (new Bus\Listeners\ListenerRegistry())
        ->add('default-listener', $listenerOptions);

    $bus = new Bus(
        new Bus\ThreadRegistry(
            new ConnectionRegistryFaker($connectionFaker),
            new Bus\Publishers\PublisherFactory(
                new ProducerStreamFactory(
                    new MessagePipelineFactory(),
                    $topicNameResolver
                )
            ),
            new Bus\Listeners\ListenerFactory(
                new ConsumerStreamFactory(
                    new MessagePipelineFactory(),
                    new ConsumerRouterFactory(
                        new NativeResolver(),
                        $topicNameResolver,
                    )
                ),
                $listenerRegistry
            )
        ),
        'default'
    );

    $bus->listen('default-listener');

    expect($connectionFaker->committedMessages)
        ->toHaveCount(1)
        ->and($connectionFaker->committedMessages['production.fact.products.1'][0]->payload)
        ->toEqual('test-message')
        ->and($connectionFaker->committedMessages['production.fact.products.1'][0]->headers)
        ->toEqual(['foo' => 'bar']);
});
