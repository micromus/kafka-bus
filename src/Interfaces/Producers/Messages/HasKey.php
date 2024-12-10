<?php

namespace Micromus\KafkaBus\Interfaces\Producers\Messages;

interface HasKey
{
    public function getKey(): ?string;
}
