<?php

namespace Micromus\KafkaBus\Interfaces\Messages;

interface HasKey
{
    public function getKey(): ?string;
}
