<?php

use Micromus\KafkaBus\Bus;
use Micromus\KafkaBus\Connections\Registry\ConnectionRegistry;
use Micromus\KafkaBus\Connections\Registry\DriverRegistry;
use Micromus\KafkaBus\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessageHandlerFactory;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouterFactory;
use Micromus\KafkaBus\Messages\MessagePipelineFactory;
use Micromus\KafkaBus\Producers\ProducerStreamFactory;
use Micromus\KafkaBus\Support\Resolvers\NativeResolver;
use Micromus\KafkaBus\Testing\Messages\ConsumerHandlerFaker;
use Micromus\KafkaBus\Topics\Topic;
use Micromus\KafkaBus\Topics\TopicRegistry;

require '../vendor/autoload.php';

$topicRegistry = (new TopicRegistry())
    ->add(new Topic('production.fact.products.1', 'products'));

$worker = new Bus\Listeners\Workers\Worker(
    'default-listener',
    (new Bus\Listeners\Workers\WorkerRoutes())
        ->add(new Bus\Listeners\Workers\Route('products', ConsumerHandlerFaker::class))
);

$listenerRegistry = (new Bus\Listeners\Workers\WorkerRegistry())
    ->add($worker);

$connectionRegistry = new ConnectionRegistry(
    new DriverRegistry(),
    [
        'default' => [
            'driver' => 'kafka',
            'options' => [
                'metadata.broker.list' => '127.0.0.1:29092',
                'group.id' => 'products-microservice',
                'log_level' => LOG_DEBUG,
//                'debug' => 'all',
            ],
        ],
    ]
);

$bus = new Bus(
    new Bus\ThreadRegistry(
        $connectionRegistry,
        new Bus\Publishers\PublisherFactory(
            new ProducerStreamFactory(new MessagePipelineFactory(new NativeResolver())),
            $topicRegistry
        ),
        new Bus\Listeners\ListenerFactory(
            new ConsumerStreamFactory(
                new ConsumerMessageHandlerFactory(
                    new MessagePipelineFactory(new NativeResolver()),
                    new ConsumerRouterFactory(new NativeResolver(), $topicRegistry)
                )
            ),
            $listenerRegistry
        )
    ),
    'default'
);

$bus->listener('default-listener')
    ->listen();
