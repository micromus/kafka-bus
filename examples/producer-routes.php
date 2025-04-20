<?php

use Micromus\KafkaBus\Interfaces\Bus\BusInterface;
use Micromus\KafkaBus\Testing\Messages\ProducerMessageFaker;

require '../vendor/autoload.php';

/** @var BusInterface $bus */
require 'bus.php';

$routes = $bus->routes();

foreach ($routes as $route) {
    echo "{$route->messageClass} => {$route->topic->name}\n";
}
