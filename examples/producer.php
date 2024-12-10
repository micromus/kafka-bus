<?php

use Micromus\KafkaBus\Interfaces\Bus\BusInterface;
use Micromus\KafkaBus\Testing\Messages\ProducerMessageFaker;

require '../vendor/autoload.php';

/** @var BusInterface $bus */
$bus = require 'bus.php';

$bus->publish(new ProducerMessageFaker('test-message', ['foo' => 'bar']));
