<?php

use Micromus\KafkaBus\Bus;
use Micromus\KafkaBus\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Messages\MessagePipelineFactory;
use Micromus\KafkaBus\Producers\ProducerStreamFactory;
use Micromus\KafkaBus\Producers\Router\ProducerRouterFactory;
use Micromus\KafkaBus\Producers\Router\ProducerRoutes;
use Micromus\KafkaBus\Support\TopicNameResolver;
use Micromus\KafkaBus\Testing\ConnectionFaker;
use Micromus\KafkaBus\Testing\ConnectionRegistryFaker;
use Micromus\KafkaBus\Testing\ProducerMessageFaker;

it('can produce message', function () {
    $connectionFaker = new ConnectionFaker;
    $routes = (new ProducerRoutes)
        ->add(ProducerMessageFaker::class, 'products');

    $bus = new Bus(
        new Bus\ThreadRegistry(
            new ConnectionRegistryFaker($connectionFaker),
            new ProducerRouterFactory(
                new ProducerStreamFactory(
                    new TopicNameResolver('production.', ['products' => 'fact.products.1']),
                    new MessagePipelineFactory
                ),
                $routes
            ),
            new ConsumerStreamFactory
        ),
        'default'
    );

    $bus->publish(new ProducerMessageFaker('test-message'));

    expect($connectionFaker->publishedMessages)
        ->toHaveCount(1)
        ->and($connectionFaker->publishedMessages['production.fact.products.1'][0]->payload)
        ->toEqual('test-message');
});
