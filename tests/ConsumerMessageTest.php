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
use Testo\Assert;
use Testo\Test;

#[Test]
function can_consume_message(): void
{
    $topicRegistry = (new TopicRegistry())
        ->add(new Topic('production.fact.products.1', 'products'));

    $connectionFaker = new ConnectionFaker($topicRegistry);

    $message = MessageFactory::for()
        ->withHeaders(['foo' => 'bar'])
        ->withTopicKey('products')
        ->make('test-message');

    $connectionFaker->addMessage($message);

    $consumerRoutes = (new ConsumerRoutes())
        ->add(new Route(
            topic: $topicRegistry->get('products'),
            handler: new VoidConsumerHandlerFaker(),
        ));

    $workerRegistry = (new Bus\Listeners\Workers\MemoryWorkerRegistry())
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

    Assert::array($connectionFaker->committedMessages)
        ->hasCount(1)
        ->hasKeys('production.fact.products.1');

    $message = $connectionFaker->committedMessages['production.fact.products.1'][0]->original();

    Assert::equals($message->payload, 'test-message');
    Assert::equals($message->headers, ['foo' => 'bar']);
}
