<?php

use Micromus\KafkaBus\Bus;
use Micromus\KafkaBus\Connections\Config\KafkaConnectionConfig;
use Micromus\KafkaBus\Connections\Registry\ConnectionRegistry;
use Micromus\KafkaBus\Connections\Registry\DriverRegistry;
use Micromus\KafkaBus\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Consumers\Handlers\MessageHandlerFactory;
use Micromus\KafkaBus\Consumers\Router\ConsumerRoutes;
use Micromus\KafkaBus\Consumers\Router\Route;
use Micromus\KafkaBus\Producers\ProducerStreamFactory;
use Micromus\KafkaBus\Support\NativeContainer;
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

$worker = new Bus\Listeners\Workers\Worker(
    'default-listener',
    (new ConsumerRoutes())
        ->add(new Route($topicRegistry->get('products'), new ConsumerHandlerFaker())),
    new Bus\Listeners\Workers\Options(additionalOptions: $consumeOptions)
);

$workerRegistry = (new Bus\Listeners\Workers\WorkerRegistry())
    ->add($worker);

$routes = (new Bus\Publishers\Router\PublisherRoutes())
    ->add(new Bus\Publishers\Router\Route(ProducerMessageFaker::class, $topicRegistry->get('products')));

$connectionRegistry = new ConnectionRegistry(
    new DriverRegistry(),
    ['default' => new KafkaConnectionConfig('127.0.0.1:29092')]
);

$container = new NativeContainer();

$publisherFactory = new Bus\Publishers\PublisherFactory(
    new ProducerStreamFactory(),
    $routes
);

$listenerFactory = new Bus\Listeners\ListenerFactory(
    new ConsumerStreamFactory(new MessageHandlerFactory()),
    $workerRegistry
);

$bus = new Bus(
    new Bus\ThreadRegistry(
        $connectionRegistry,
        new Bus\ThreadFactory(
            $listenerFactory,
            $publisherFactory,
        )
    ),
    'default'
);
