<?php

namespace Micromus\KafkaBus\Consumers\Commiters;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;

class VoidCommiter implements CommiterInterface
{
    public function commit(ConsumerMessage $consumerMessage): void
    {
    }
}
