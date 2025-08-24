<?php

namespace Micromus\KafkaBus\Interfaces\Producers\Messages;

use Stringable;

interface HasHeaders
{
    /**
     * @return array<string, string|Stringable>
     */
    public function getHeaders(): array;
}
