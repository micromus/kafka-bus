<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Connections\Kafka;

use Micromus\KafkaBus\Connections\KafkaConnectionConfig;
use Micromus\KafkaBus\Producers\ProducerConfig;
use RdKafka\Producer as KafkaProducer;
use RdKafka\Producer as ProducerProducer;

final class KafkaProducerFactory
{
    private KafkaConfConverter $confConverter;

    public function __construct(
        private readonly KafkaConnectionConfig $connectionConfig,
    ) {
        $this->confConverter = new KafkaConfConverter();
    }

    public function make(ProducerConfig $config): ProducerProducer
    {
        $options = $this->connectionConfig
            ->getProducerOptions($config->additionalOptions);

        return new KafkaProducer($this->confConverter->fromArray($options));
    }
}
