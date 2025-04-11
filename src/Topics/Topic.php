<?php

namespace Micromus\KafkaBus\Topics;

final readonly class Topic
{
    public function __construct(
        public string $name,
        public string $key
    ) {
    }
}
