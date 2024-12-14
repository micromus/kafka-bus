<?php

use Micromus\KafkaBus\Interfaces\Bus\BusInterface;

require '../vendor/autoload.php';

/** @var BusInterface $bus */
$bus = require 'bus.php';

pcntl_async_signals(true);

$listener = $bus->createListener('default-listener');

pcntl_signal(SIGINT, fn () => $listener->forceStop());

$listener->listen();

