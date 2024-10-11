<?php

namespace Micromus\KafkaBus\Connections;

use Micromus\KafkaBus\Consumers\Commiters\DefaultCommiter;
use Micromus\KafkaBus\Consumers\Commiters\VoidCommiter;
use Micromus\KafkaBus\Consumers\Configuration as ConsumerConfiguration;
use Micromus\KafkaBus\Consumers\Consumer;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionInterface;
use Micromus\KafkaBus\Interfaces\Consumers\ConsumerInterface;
use Micromus\KafkaBus\Interfaces\Producers\ProducerInterface;
use Micromus\KafkaBus\Producers\Configuration as ProducerConfiguration;
use Micromus\KafkaBus\Producers\Producer;
use Micromus\KafkaBus\Support\RetryRepeater;
use RdKafka\Conf;
use RdKafka\KafkaConsumer;
use RdKafka\Producer as KafkaProducer;

class KafkaConnection implements ConnectionInterface
{
    protected KafkaConnectionConfiguration $configuration;

    public function __construct(array $options)
    {
        $this->configuration = new KafkaConnectionConfiguration($options);
    }

    public function createProducer(string $topicName, ProducerConfiguration $configuration): ProducerInterface
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

    public function createConsumer(array $topicNames, ConsumerConfiguration $configuration): ConsumerInterface
    {
        $consumer = $this->makeKafkaConsumer($configuration);

        return new Consumer(
            consumer: $consumer,
            topicNames: $topicNames,
            commiter: $configuration->autoCommit ? new DefaultCommiter($consumer) : new VoidCommiter(),
            retryRepeater: new RetryRepeater(),
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
        $conf = new Conf();

        foreach ($options as $key => $value) {
            $conf->set($key, $value);
        }

        return $conf;
    }
}
