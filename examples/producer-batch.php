<?php

use Micromus\KafkaBus\Bus\MessageBatch;
use Micromus\KafkaBus\Interfaces\Bus\BusInterface;
use Micromus\KafkaBus\Testing\Messages\ProducerMessageFaker;

require '../vendor/autoload.php';

/** @var BusInterface $bus */
require 'bus.php';

$messageBatch = MessageBatch::empty(ProducerMessageFaker::class);
$time = microtime(true);

foreach (range(1, 50) as $i) {
    $messageBatch->add(new ProducerMessageFaker("$time-test-message-$i"));
}

$bus->publishBatch($messageBatch);
