<?php

namespace Micromus\KafkaBus\Interfaces\Producers\Messages;

interface HasHeaders
{
    /**
     * @return array<string, mixed>
     */
    public function getHeaders(): array;
}
