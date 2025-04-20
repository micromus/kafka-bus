<?php

namespace Micromus\KafkaBus\Interfaces\Connections;

use Micromus\KafkaBus\Interfaces\Connections\Topics\ConnectionTopicsInterface;
use Micromus\KafkaBus\Interfaces\Topics\TopicRepositoryInterface;

interface ConnectionHasTopicsInterface
{
    public function topics(): ConnectionTopicsInterface;
}
