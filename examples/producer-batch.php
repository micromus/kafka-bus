<?php

use Micromus\KafkaBus\Bus\MessageBatch;
use Micromus\KafkaBus\Interfaces\Bus\BusInterface;
use Micromus\KafkaBus\Testing\Messages\ProducerMessageFaker;

require '../vendor/autoload.php';

/** @var BusInterface $bus */
require 'bus.php';

$time = microtime(true);
$messages = [];

foreach (range(1, 50) as $i) {
    $messages[] = new ProducerMessageFaker("$time-test-message-$i");
}

$bus->publishBatch(MessageBatch::fromArray($messages));
