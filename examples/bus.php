<?php

use Micromus\KafkaBus\Bus;
use Micromus\KafkaBus\Bus\Publishers\Router\PublisherRoutesBuilder;
use Micromus\KafkaBus\Connections\Registry\ConnectionRegistry;
use Micromus\KafkaBus\Consumers\Router\ConsumerRoutesBuilder;
use Micromus\KafkaBus\Consumers\Router\RouteInfo;
use Micromus\KafkaBus\Testing\Messages\ConsumerHandlerFaker;
use Micromus\KafkaBus\Testing\Messages\ProducerMessageFaker;
use Micromus\KafkaBus\Topics\Topic;
use Micromus\KafkaBus\Topics\TopicRegistry;

$topicRegistry = (new TopicRegistry())
    ->add(new Topic('production.fact.products.1', 'products'));

$consumeOptions = [
    'group.id' => 'products-microservice',
    'auto.offset.reset' => 'beginning',
];

$consumerRoutes = ConsumerRoutesBuilder::make($topicRegistry)
    ->add(new RouteInfo('products', new ConsumerHandlerFaker()))
    ->build();

$publisherRoutes = PublisherRoutesBuilder::make($topicRegistry)
    ->add(ProducerMessageFaker::class, 'products')
    ->build();

$workerRegistry = Bus\Listeners\Workers\MemoryWorkerRegistry::make()
    ->add(
        new Bus\Listeners\Workers\Worker(
            'default-listener',
            $consumerRoutes,
            new Bus\Listeners\Workers\Options(additionalOptions: $consumeOptions)
        )
    );

$bus = new Bus(
    new Bus\ThreadRegistry(
        ConnectionRegistry::default(),
        new Bus\ThreadFactory(
            new Bus\Listeners\ListenerFactory(workerRegistry: $workerRegistry),
            new Bus\Publishers\PublisherFactory(routes: $publisherRoutes),
        )
    ),
    ConnectionRegistry::DEFAULT_CONNECTION_NAME
);
