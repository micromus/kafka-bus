<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Interfaces\Connections;

use Micromus\KafkaBus\Connections\Config\Options;

interface ConnectionConfigInterface
{
    public function getOptions(): Options;
}
