<?php

namespace Micromus\KafkaBus\Connections;

use Micromus\KafkaBus\Consumers\Commiters\DefaultCommiter;
use Micromus\KafkaBus\Consumers\Commiters\VoidCommiter;
use Micromus\KafkaBus\Consumers\Configuration as ConsumerConfiguration;
use Micromus\KafkaBus\Consumers\Consumer;
use Micromus\KafkaBus\Contracts\Connections\Connection;
use Micromus\KafkaBus\Contracts\Consumers\Consumer as ConsumerContract;
use Micromus\KafkaBus\Contracts\Producers\Producer as ProducerContract;
use Micromus\KafkaBus\Producers\Configuration as ProducerConfiguration;
use Micromus\KafkaBus\Producers\Producer;
use Micromus\KafkaBus\Support\RetryRepeater;
use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\Producer as KafkaProducer;

class KafkaConnection implements Connection
{
    protected KafkaConnectionConfiguration $configuration;

    public function __construct(array $options)
    {
        $this->configuration = new KafkaConnectionConfiguration($options);
    }

    public function createProducer(string $topicName, ProducerConfiguration $configuration): ProducerContract
    {
        return new Producer(
            producer: $this->makeKafkaProducer($configuration),
            topicName: $topicName,
            retryRepeater: new RetryRepeater($configuration->flushRetries),
            timeout: $configuration->flushTimeout
        );
    }

    private function makeKafkaProducer(ProducerConfiguration $configuration): KafkaProducer
    {
        $options = $this->configuration
            ->getProducerOptions($configuration->additionalOptions);

        return new KafkaProducer($this->makeConf($options));
    }

    public function createConsumer(array $topicNames, ConsumerConfiguration $configuration): ConsumerContract
    {
        $consumer = $this->makeKafkaConsumer($configuration);

        return new Consumer(
            consumer: $consumer,
            topicNames: $topicNames,
            commiter: $configuration->autoCommit ? new DefaultCommiter($consumer) : new VoidCommiter,
            retryRepeater: new RetryRepeater,
            consumerTimeout: $configuration->consumerTimeout,
        );
    }

    private function makeKafkaConsumer(ConsumerConfiguration $configuration): KafkaConsumer
    {
        $options = $this->configuration
            ->getConsumerOptions($configuration->additionalOptions);

        $options['enable.auto.commit'] = $configuration->autoCommit ? 'true' : 'false';

        return new KafkaConsumer($this->makeConf($options));
    }

    private function makeConf(array $options): Conf
    {
        $conf = new Conf;

        foreach ($options as $key => $value) {
            $conf->set($key, $value);
        }

        return $conf;
    }
}
