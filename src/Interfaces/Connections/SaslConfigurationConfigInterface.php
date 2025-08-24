<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Interfaces\Connections;

interface SaslConfigurationConfigInterface
{
    /**
     * @return array<string, bool|int|string|null>
     */
    public function toOptions(): array;
}
