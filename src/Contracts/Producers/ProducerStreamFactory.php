<?php

namespace Micromus\KafkaBus\Contracts\Producers;

use Micromus\KafkaBus\Bus\Publishers\Router\Options;
use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Topics\Topic;

interface ProducerStreamFactory
{
    public function create(Connection $connection, Topic $topic, Options $options): ProducerStream;
}
