<?php

namespace Micromus\KafkaBus\Consumers\Commiters;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;

interface CommiterInterface
{
    public function commit(ConsumerMessage $consumerMessage): void;
}
