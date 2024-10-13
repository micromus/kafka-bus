<?php

use Micromus\KafkaBus\Bus;
use Micromus\KafkaBus\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouterFactory;
use Micromus\KafkaBus\Messages\MessagePipelineFactory;
use Micromus\KafkaBus\Producers\ProducerStreamFactory;
use Micromus\KafkaBus\Support\Resolvers\NativeResolver;
use Micromus\KafkaBus\Testing\Connections\ConnectionFaker;
use Micromus\KafkaBus\Testing\Connections\ConnectionRegistryFaker;
use Micromus\KafkaBus\Testing\Messages\VoidConsumerHandlerFaker;
use Micromus\KafkaBus\Topics\Topic;
use Micromus\KafkaBus\Topics\TopicRegistry;
use RdKafka\Message;

test('can consume message', function () {
    $topicRegistry = (new TopicRegistry())
        ->add(new Topic('production.fact.products.1', 'products', 6));

    $connectionFaker = new ConnectionFaker($topicRegistry);

    $message = new Message();
    $message->payload = 'test-message';
    $message->headers = ['foo' => 'bar'];

    $connectionFaker->addMessage('products', $message);

    $worker = new Bus\Listeners\Workers\Worker(
        (new Bus\Listeners\Workers\WorkerRoutes())
            ->add(new Bus\Listeners\Workers\Route('products', VoidConsumerHandlerFaker::class))
    );

    $listenerRegistry = (new Bus\Listeners\Workers\WorkerRegistry())
        ->add('default-listener', $worker);

    $bus = new Bus(
        new Bus\ThreadRegistry(
            new ConnectionRegistryFaker($connectionFaker),
            new Bus\Publishers\PublisherFactory(
                new ProducerStreamFactory(new MessagePipelineFactory()),
                $topicRegistry
            ),
            new Bus\Listeners\ListenerFactory(
                new ConsumerStreamFactory(
                    new MessagePipelineFactory(),
                    new ConsumerRouterFactory(new NativeResolver(), $topicRegistry)
                ),
                $listenerRegistry
            )
        ),
        'default'
    );

    $bus->listener('default-listener')
        ->listen();

    expect($connectionFaker->committedMessages)
        ->toHaveCount(1)
        ->and($connectionFaker->committedMessages['production.fact.products.1'][0]->payload)
        ->toEqual('test-message')
        ->and($connectionFaker->committedMessages['production.fact.products.1'][0]->headers)
        ->toEqual(['foo' => 'bar']);
});
