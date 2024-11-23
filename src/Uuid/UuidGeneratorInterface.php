<?php

namespace Micromus\KafkaBus\Uuid;

use Ramsey\Uuid\UuidInterface;

interface UuidGeneratorInterface
{
    public function generate(): UuidInterface;
}
