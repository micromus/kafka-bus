<?php

namespace Micromus\KafkaBus\Interfaces\Producers\Messages;

interface HasHeaders
{
    public function getHeaders(): array;
}
