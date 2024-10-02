<?php

use Micromus\KafkaBus\Bus;
use Micromus\KafkaBus\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouterFactory;
use Micromus\KafkaBus\Messages\MessagePipelineFactory;
use Micromus\KafkaBus\Producers\ProducerStreamFactory;
use Micromus\KafkaBus\Support\Resolvers\NativeResolver;
use Micromus\KafkaBus\Testing\ConnectionFaker;
use Micromus\KafkaBus\Testing\ConnectionRegistryFaker;
use Micromus\KafkaBus\Testing\ConsumerHandlerFaker;
use Micromus\KafkaBus\Topics\Topic;
use Micromus\KafkaBus\Topics\TopicRegistry;
use RdKafka\Message;

test('can consume message', function () {
    $topicRegistry = (new TopicRegistry)
        ->add(new Topic('production.fact.products.1', 'products', 6));

    $connectionFaker = new ConnectionFaker($topicRegistry);

    $message = new Message;
    $message->payload = 'test-message';
    $message->headers = ['foo' => 'bar'];

    $connectionFaker->addMessage('products', $message);

    $group = new Bus\Listeners\Groups\Group(
        (new Bus\Listeners\Groups\GroupRoutes)
            ->add(new Bus\Listeners\Groups\Route('products', ConsumerHandlerFaker::class))
    );

    $listenerRegistry = (new Bus\Listeners\Groups\GroupRegistry)
        ->add('default-listener', $group);

    $bus = new Bus(
        new Bus\ThreadRegistry(
            new ConnectionRegistryFaker($connectionFaker),
            new Bus\Publishers\PublisherFactory(
                new ProducerStreamFactory(new MessagePipelineFactory),
                $topicRegistry
            ),
            new Bus\Listeners\ListenerFactory(
                new ConsumerStreamFactory(
                    new MessagePipelineFactory,
                    new ConsumerRouterFactory(new NativeResolver, $topicRegistry)
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
