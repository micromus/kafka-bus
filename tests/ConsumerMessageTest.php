<?php

use Micromus\KafkaBus\Bus;
use Micromus\KafkaBus\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessageHandlerFactory;
use Micromus\KafkaBus\Consumers\Router\ConsumerRouterFactory;
use Micromus\KafkaBus\Pipelines\PipelineFactory;
use Micromus\KafkaBus\Producers\ProducerStreamFactory;
use Micromus\KafkaBus\Support\Resolvers\NativeResolver;
use Micromus\KafkaBus\Testing\Connections\ConnectionFaker;
use Micromus\KafkaBus\Testing\Connections\ConnectionRegistryFaker;
use Micromus\KafkaBus\Testing\Consumers\MessageBuilder;
use Micromus\KafkaBus\Testing\Messages\VoidConsumerHandlerFaker;
use Micromus\KafkaBus\Topics\Topic;
use Micromus\KafkaBus\Topics\TopicRegistry;

test('can consume message', function () {
    $topicRegistry = (new TopicRegistry())
        ->add(new Topic('production.fact.products.1', 'products'));

    $connectionFaker = new ConnectionFaker();

    $message = MessageBuilder::for($topicRegistry)
        ->build([
            'topic_name' => 'products',
            'payload' => 'test-message',
            'headers' => ['foo' => 'bar'],
        ]);

    $connectionFaker->addMessage($message);

    $workerRegistry = (new Bus\Listeners\Workers\WorkerRegistry())
        ->add(
            new Bus\Listeners\Workers\Worker(
                'default-listener',
                (new Bus\Listeners\Workers\WorkerRoutes())
                    ->add(new Bus\Listeners\Workers\Route('products', VoidConsumerHandlerFaker::class))
            )
        );

    $bus = new Bus(
        new Bus\ThreadRegistry(
            new ConnectionRegistryFaker($connectionFaker),
            new Bus\Publishers\PublisherFactory(
                new ProducerStreamFactory(new PipelineFactory(new NativeResolver())),
                $topicRegistry
            ),
            new Bus\Listeners\ListenerFactory(
                new ConsumerStreamFactory(
                    new ConsumerMessageHandlerFactory(
                        new PipelineFactory(new NativeResolver()),
                        new ConsumerRouterFactory(
                            new NativeResolver(),
                            new PipelineFactory(new NativeResolver()),
                            $topicRegistry
                        )
                    )
                ),
                $workerRegistry
            )
        ),
        'default'
    );

    $bus->createListener('default-listener')
        ->listen();

    expect($connectionFaker->committedMessages)
        ->toHaveCount(1)
        ->and($connectionFaker->committedMessages['production.fact.products.1'][0]->original())
        ->toHaveProperties([
            'payload' => 'test-message',
            'headers' => ['foo' => 'bar'],
        ]);
});
