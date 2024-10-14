<?php

namespace Micromus\KafkaBus\Topics;

readonly class Topic
{
    public function __construct(
        public string $name,
        public string $key
    ) {
    }
}
