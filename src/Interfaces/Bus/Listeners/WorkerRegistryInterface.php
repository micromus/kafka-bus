<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Interfaces\Bus\Listeners;

use Micromus\KafkaBus\Bus\Listeners\Workers\Worker;

interface WorkerRegistryInterface
{
    public function get(string $workerName): ?Worker;
}
