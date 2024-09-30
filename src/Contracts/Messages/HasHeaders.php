<?php

namespace Micromus\KafkaBus\Contracts\Messages;

interface HasHeaders
{
    public function getHeaders(): array;
}
