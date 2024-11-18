<?php

use Micromus\KafkaBus\Bus;
use Micromus\KafkaBus\Bus\Publishers\Router\PublisherRoutes;
use Micromus\KafkaBus\Connections\Registry\ConnectionRegistry;
use Micromus\KafkaBus\Connections\Registry\DriverRegistry;
use Micromus\KafkaBus\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessageHandlerFactory;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouterFactory;
use Micromus\KafkaBus\Messages\MessagePipelineFactory;
use Micromus\KafkaBus\Producers\ProducerStreamFactory;
use Micromus\KafkaBus\Support\Resolvers\NativeResolver;
use Micromus\KafkaBus\Testing\Messages\ProducerMessageFaker;
use Micromus\KafkaBus\Topics\Topic;
use Micromus\KafkaBus\Topics\TopicRegistry;

require '../vendor/autoload.php';

$topicRegistry = (new TopicRegistry())
    ->add(new Topic('production.fact.products.1', 'products'));

$routes = (new PublisherRoutes())
    ->add(new Bus\Publishers\Router\Route(ProducerMessageFaker::class, 'products'));

$connectionRegistry = new ConnectionRegistry(
    new DriverRegistry(),
    [
        'default' => [
            'driver' => 'kafka',
            'options' => [
                'metadata.broker.list' => '127.0.0.1:29092',
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

$bus->publish(new ProducerMessageFaker('test-message', ['foo' => 'bar']));
