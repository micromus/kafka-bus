<?php

namespace Micromus\KafkaBus\Interfaces\Messages;

interface HasHeaders
{
    public function getHeaders(): array;
}
