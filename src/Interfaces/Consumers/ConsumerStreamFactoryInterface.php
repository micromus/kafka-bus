<?php

namespace Micromus\KafkaBus\Interfaces\Consumers;

use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;

interface ConsumerStreamFactoryInterface
{
    public function create(ConnectionInterface $connection, Worker $worker): ConsumerStreamInterface;
}
