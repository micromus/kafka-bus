<?php

namespace Micromus\KafkaBus\Producers\Messages;

class ProducerMessage
{
    /**
     * @param  array<string, string>  $headers
     */
    public function __construct(
        public string $payload,
        public array $headers = [],
        public int $partition = RD_KAFKA_PARTITION_UA,
        public ?string $key = null
    ) {
    }
}
