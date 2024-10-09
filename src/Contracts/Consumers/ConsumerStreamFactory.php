<?php

namespace Micromus\KafkaBus\Contracts\Consumers;

use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;
use Micromus\KafkaBus\Contracts\Connections\Connection;

interface ConsumerStreamFactory
{
    public function create(Connection $connection, Worker $worker): ConsumerStream;
}
