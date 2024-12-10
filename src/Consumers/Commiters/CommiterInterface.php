<?php

namespace Micromus\KafkaBus\Consumers\Commiters;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;

interface CommiterInterface
{
    public function commit(ConsumerMessageInterface $consumerMessage): void;
}
