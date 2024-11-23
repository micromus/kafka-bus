<?php

namespace Micromus\KafkaBus\Uuid;

use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\UuidFactoryInterface;
use Ramsey\Uuid\UuidInterface;

final class RandomUuidGenerator implements UuidGeneratorInterface
{
    protected UuidFactoryInterface $factory;

    public function __construct()
    {
        $this->factory = new UuidFactory();
    }

    public function generate(): UuidInterface
    {
        return $this->factory->uuid4();
    }
}
