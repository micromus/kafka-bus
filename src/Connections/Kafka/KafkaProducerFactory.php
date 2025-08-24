<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Connections\Kafka;

use Micromus\KafkaBus\Connections\Config\Options;
use Micromus\KafkaBus\Producers\ProducerConfig;
use RdKafka\Producer as KafkaProducer;

final class KafkaProducerFactory
{
    private KafkaConfConverter $confConverter;

    public function __construct(
        private readonly Options $options,
    ) {
        $this->confConverter = new KafkaConfConverter();
    }

    public function make(ProducerConfig $config): KafkaProducer
    {
        $options = $this->options
            ->getProducerOptions($config->additionalOptions);

        return new KafkaProducer($this->confConverter->fromArray($options));
    }
}
