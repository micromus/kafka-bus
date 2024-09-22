<?php

namespace Micromus\KafkaBus\Consumers;

use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Consumers\ConsumerStream as ConsumerStreamContract;
use Micromus\KafkaBus\Contracts\Consumers\ConsumerStreamFactory as ConsumerStreamFactoryContract;

class ConsumerStreamFactory implements ConsumerStreamFactoryContract
{
    public function create(Connection $connection, ?string $listenerName = null): ConsumerStreamContract
    {
        return new ConsumerStream;
    }
}
