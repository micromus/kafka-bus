# This is my package laravel-kafka-bus

[![Latest Version on Packagist](https://img.shields.io/packagist/v/micromus/kafka-bus.svg?style=flat-square)](https://packagist.org/packages/micromus/laravel-kafka-bus)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/micromus/kafka-bus/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/micromus/laravel-kafka-bus/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/micromus/kafka-bus/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/micromus/laravel-kafka-bus/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/micromus/kafka-bus.svg?style=flat-square)](https://packagist.org/packages/micromus/laravel-kafka-bus)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require micromus/laravel-kafka-bus
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-kafka-bus-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-kafka-bus-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-kafka-bus-views"
```

## Usage

```php
$kafkaBus = new Micromus\KafkaBus();
echo $kafkaBus->echoPhrase('Hello, Micromus!');
```

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

- [Micromus](https://github.com/kEERill)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
