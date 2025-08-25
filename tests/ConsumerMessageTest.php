<?php

use Micromus\KafkaBus\Bus;
use Micromus\KafkaBus\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Consumers\Handlers\MessageHandlerFactory;
use Micromus\KafkaBus\Consumers\Router\ConsumerRoutes;
use Micromus\KafkaBus\Consumers\Router\Route;
use Micromus\KafkaBus\Producers\ProducerStreamFactory;
use Micromus\KafkaBus\Testing\Connections\ConnectionFaker;
use Micromus\KafkaBus\Testing\Connections\ConnectionRegistryFaker;
use Micromus\KafkaBus\Testing\Consumers\MessageFactory;
use Micromus\KafkaBus\Testing\Messages\VoidConsumerHandlerFaker;
use Micromus\KafkaBus\Topics\Topic;
use Micromus\KafkaBus\Topics\TopicRegistry;

test('can consume message', function () {
    $topicRegistry = (new TopicRegistry())
        ->add(new Topic('production.fact.products.1', 'products'));

    $connectionFaker = new ConnectionFaker();

    $message = MessageFactory::for($topicRegistry)
        ->withHeaders(['foo' => 'bar'])
        ->withTopicKey('products')
        ->make('test-message');

    $connectionFaker->addMessage($message);

    $consumerRoutes = (new ConsumerRoutes())
        ->add(new Route(
            topic: $topicRegistry->get('products'),
            handler: new VoidConsumerHandlerFaker(),
        ));

    $workerRegistry = (new Bus\Listeners\Workers\WorkerRegistry())
        ->add(new Bus\Listeners\Workers\Worker('default-listener', $consumerRoutes));

    $bus = new Bus(
        new Bus\ThreadRegistry(
            new ConnectionRegistryFaker($connectionFaker),
            new Bus\ThreadFactory(
                new Bus\Listeners\ListenerFactory(
                    new ConsumerStreamFactory(new MessageHandlerFactory()),
                    $workerRegistry
                ),
                new Bus\Publishers\PublisherFactory(
                    new ProducerStreamFactory(),
                )
            )
        ),
        'default'
    );

    $bus->listener('default-listener')
        ->listen();

    expect($connectionFaker->committedMessages)
        ->toHaveCount(1)
        ->and($connectionFaker->committedMessages['production.fact.products.1'][0]->original())
        ->toHaveProperties([
            'payload' => 'test-message',
            'headers' => ['foo' => 'bar'],
        ]);
});
