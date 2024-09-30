<?php

namespace Micromus\KafkaBus\Consumers\Commiters;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;

interface Commiter
{
    public function commit(ConsumerMessage $consumerMessage): void;
}
