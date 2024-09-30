<?php

namespace Micromus\KafkaBus\Contracts\Consumers;

use Micromus\KafkaBus\Bus\Listeners\Options;
use Micromus\KafkaBus\Contracts\Connections\Connection;

interface ConsumerStreamFactory
{
    public function create(Connection $connection, Options $options): ConsumerStream;
}
