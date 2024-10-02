<?php

namespace Micromus\KafkaBus\Topics;

use Webmozart\Assert\Assert;

readonly class Topic
{
    public function __construct(
        public string $name,
        public string $key,
        public int $partitions = 1,
    ) {
        Assert::greaterThanEq($this->partitions, 1, 'Количество партиций должно быть больше или равно 1. Дано: %s');
    }
}
