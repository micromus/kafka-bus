<?php

namespace Micromus\KafkaBus\Interfaces\Consumers\Messages;

interface WorkerConsumerMessageInterface extends ConsumerMessageInterface
{
    public function workerName(): string;
}
