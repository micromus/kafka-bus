<?php

namespace Micromus\KafkaBus\Interfaces\Producers\Messages;

interface HasPartition
{
    public function getPartition(): int;
}
