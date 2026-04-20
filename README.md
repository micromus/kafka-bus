# Kafka Bus for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/micromus/kafka-bus.svg?style=flat-square)](https://packagist.org/packages/micromus/kafka-bus)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/micromus/kafka-bus/run-tests.yml?branch=1.x&label=tests&style=flat-square)](https://github.com/micromus/kafka-bus/actions?query=workflow%3Arun-tests+branch%3A1.x)
[![GitHub Code Style](https://img.shields.io/github/actions/workflow/status/micromus/kafka-bus/php-code-style.yml?branch=1.x&label=code-style&style=flat-square)](https://github.com/micromus/kafka-bus/actions?query=workflow%3Acode-style+branch%3A1.x)
[![GitHub PHPStan](https://img.shields.io/github/actions/workflow/status/micromus/kafka-bus/phpstan.yml?branch=1.x&label=phpstan&style=flat-square)](https://github.com/micromus/kafka-bus/actions?query=workflow%3Aphpstan+branch%3A1.x)
[![Total Downloads](https://img.shields.io/packagist/dt/micromus/kafka-bus.svg?style=flat-square)](https://packagist.org/packages/micromus/kafka-bus)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require micromus/kafka-bus
```

### Requirements

- PHP ^8.2
- `ext-rdkafka` and a running Kafka cluster
- Optional for consumers: `ext-pcntl` (to handle stop signals gracefully)

## Usage (via Composer)

### Quick start: Bus with producer and consumer

Below is a minimal example of wiring the bus, registering a topic, adding a producer route, and running a listener that handles messages from the same topic.

```php
<?php

use Micromus\KafkaBus\Bus;
use Micromus\KafkaBus\Connections\Config\KafkaConnectionConfig;
use Micromus\KafkaBus\Connections\Registry\ConnectionRegistry;
use Micromus\KafkaBus\Connections\Registry\DriverRegistry;
use Micromus\KafkaBus\Consumers\ConsumerStreamFactory;
use Micromus\KafkaBus\Consumers\Handlers\MessageHandler;
use Micromus\KafkaBus\Consumers\Handlers\MessageHandlerFactory;
use Micromus\KafkaBus\Consumers\Router\ConsumerRoutes;
use Micromus\KafkaBus\Consumers\Router\Route as ConsumerRoute;
use Micromus\KafkaBus\Producers\Messages\ProducerMessage;
use Micromus\KafkaBus\Producers\ProducerStreamFactory;
use Micromus\KafkaBus\Topics\Topic;
use Micromus\KafkaBus\Topics\TopicRegistry;

require __DIR__ . '/vendor/autoload.php';

// Define topics
$topicRegistry = (new TopicRegistry())
    ->add(new Topic('production.fact.products.1', 'products'));

// Create consumer worker (listener) that handles messages from the topic
$consumeOptions = [
    'group.id' => 'products-microservice',
    'auto.offset.reset' => 'earliest',
];

// Simple synchronous handler example
class PrintHandler implements MessageHandler {
    public function handle(object $message): void {
        // $message is your domain message from pipeline
        fwrite(STDOUT, "Handled: " . json_encode($message) . PHP_EOL);
    }
}

$consumerRoutes = ConsumerRoutesBuilder::make($topicRegistry)
    ->add(new RouteInfo('products', new PrintHandler()))
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

// Produce a message
$bus->publish(new ProducerMessage(payload: 'test-message', headers: ['foo' => 'bar']));

// Consume in the same process (or run it separately)
pcntl_async_signals(true);
$listener = $bus->listener('default-listener');
pcntl_signal(SIGINT, fn () => $listener->forceStop());

$listener->listen();
```

### Producing only

If you only need to produce messages, configure the bus and call `publish` with `ProducerMessage` instances. You do not need to start a listener in that case.

### Consuming only

If you only need to consume, configure the worker(s) and call `listener('name')->listen()`. Your `MessageHandler` implementation will be invoked for each message received.

### More examples

- Producer only: see `examples/producer.php`
- Consumer only: see `examples/consumer.php`
- Full setup with routing: see `examples/bus.php`

## Architecture

The architecture of the package is described in [STRUCTURE.md](STRUCTURE.md) (in Russian).

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Kirill Popkov](https://github.com/popkovkirill)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
