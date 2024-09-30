<?php

namespace Micromus\KafkaBus\Contracts\Messages;

interface HasPartition
{
    public function getPartition(): int;
}
