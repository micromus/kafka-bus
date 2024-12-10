<?php

namespace Micromus\KafkaBus\Consumers\Commiters;

use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;

class VoidCommiter implements CommiterInterface
{
    public function commit(ConsumerMessageInterface $consumerMessage): void
    {
    }
}
