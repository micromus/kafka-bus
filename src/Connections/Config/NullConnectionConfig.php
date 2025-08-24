<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Connections\Config;

use Micromus\KafkaBus\Interfaces\Connections\ConnectionConfigInterface;

final readonly class NullConnectionConfig implements ConnectionConfigInterface
{
    public function getOptions(): Options
    {
        return new Options();
    }
}
