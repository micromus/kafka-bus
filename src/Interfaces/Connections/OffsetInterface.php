<?php

namespace Micromus\KafkaBus\Interfaces\Connections;

interface OffsetInterface
{
    public function toValue(): int|string;
}
