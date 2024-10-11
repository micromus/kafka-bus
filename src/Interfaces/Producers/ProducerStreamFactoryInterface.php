<?php

namespace Micromus\KafkaBus\Interfaces\Producers;

use Micromus\KafkaBus\Bus\Publishers\Router\Options;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Topics\Topic;

interface ProducerStreamFactoryInterface
{
    public function create(ConnectionInterface $connection, Topic $topic, Options $options): ProducerStreamInterface;
}
