<?php

namespace Micromus\KafkaBus\Contracts\Consumers;

use Micromus\KafkaBus\Bus\Listeners\Groups\Group;
use Micromus\KafkaBus\Contracts\Connections\Connection;

interface ConsumerStreamFactory
{
    public function create(Connection $connection, Group $group): ConsumerStream;
}
