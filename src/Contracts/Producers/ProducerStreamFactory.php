<?php

namespace Micromus\KafkaBus\Contracts\Producers;

use Micromus\KafkaBus\Bus\Publishers\Options;
use Micromus\KafkaBus\Contracts\Connections\Connection;

interface ProducerStreamFactory
{
    public function create(Connection $connection, string $topicKey, Options $options): ProducerStream;
}
