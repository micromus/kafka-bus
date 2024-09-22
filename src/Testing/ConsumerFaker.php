<?php

namespace Micromus\KafkaBus\Testing;

use Exception;
use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Micromus\KafkaBus\Contracts\Consumers\Consumer;

class ConsumerFaker implements Consumer
{
    public function __construct(
        protected array $messages
    ) {}

    public function getMessage(): ConsumerMessage
    {
        if (count($this->messages) == 0) {
            throw new Exception;
        }

        return array_shift($this->messages);
    }

    public function commit(ConsumerMessage $consumerMessage): void {}
}
