<?php

namespace Micromus\KafkaBus\Interfaces\Messages;

interface HasPartition
{
    public function getPartition(): int;
}
