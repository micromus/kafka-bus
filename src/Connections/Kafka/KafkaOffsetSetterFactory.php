<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Connections\Kafka;

use Micromus\KafkaBus\Connections\Offsets\OffsetConnectionSetter;
use Micromus\KafkaBus\Consumers\ConsumerConfig;

final readonly class KafkaOffsetSetterFactory
{
    public function __construct(
        private KafkaConsumerFactory $consumerFactory,
    ) {
    }

    public function make(ConsumerConfig $config): OffsetConnectionSetter
    {
        return new OffsetConnectionSetter($this->consumerFactory->make($config));
    }
}
