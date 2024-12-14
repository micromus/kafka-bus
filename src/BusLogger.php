<?php

namespace Micromus\KafkaBus;

use Micromus\KafkaBus\Interfaces\BusLoggerInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;
use Stringable;

final class BusLogger implements BusLoggerInterface
{
    use LoggerTrait;

    public function __construct(
        protected LoggerInterface $logger
    ) {
    }

    public function log($level, Stringable|string $message, array $context = []): void
    {
        $this->logger->log($level, $message, $context);
    }
}
