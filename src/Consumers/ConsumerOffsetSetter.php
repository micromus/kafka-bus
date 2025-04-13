<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Consumers;

use Micromus\KafkaBus\Bus\Listeners\Workers\Options;
use Micromus\KafkaBus\Bus\Listeners\Workers\WorkerRegistry;
use Micromus\KafkaBus\Exceptions\CannotSetOffsetForPartitionsException;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionOffsetInterface;
use Micromus\KafkaBus\Interfaces\Connections\ConnectionRegistryInterface;
use Micromus\KafkaBus\Topics\Partition;
use Micromus\KafkaBus\Topics\TopicRegistry;

final class ConsumerOffsetSetter
{
    public function __construct(
        protected TopicRegistry $topicRegistry,
        protected WorkerRegistry $workerRegistry,
        protected ConnectionRegistryInterface $connectionRegistry
    ) {
    }

    /**
     * @param string $connectionName
     * @param ConsumerOffset $consumerOffset
     * @return int[]
     *
     * @throws CannotSetOffsetForPartitionsException
     */
    public function set(string $connectionName, ConsumerOffset $consumerOffset): array
    {
        $topic = $this->topicRegistry->get($consumerOffset->topicKey);
        $partition = new Partition($topic, $consumerOffset->partition);

        $connection = $this->connectionRegistry->connection($connectionName);

        if (!$connection instanceof ConnectionOffsetInterface) {
            throw new CannotSetOffsetForPartitionsException(
                "Connection doesn't support offset setting \"{$connectionName}\""
            );
        }

        $worker = $this->workerRegistry->get($consumerOffset->workerName);

        if (\is_null($worker)) {
            throw new CannotSetOffsetForPartitionsException(
                "Worker #{$consumerOffset->workerName} does not exist"
            );
        }

        if (!$worker->routes->has($consumerOffset->topicKey)) {
            throw new CannotSetOffsetForPartitionsException(
                "Worker #{$consumerOffset->workerName} does not contain topic \"{$consumerOffset->topicKey}\""
            );
        }

        $consumerConfig = $this->makeConsumerConfig($worker->options);

        return $connection->setOffset($partition, $consumerOffset->offset, $consumerConfig);
    }

    private function makeConsumerConfig(Options $options): ConsumerConfig
    {
        return new ConsumerConfig(
            additionalOptions: $options->additionalOptions,
            autoCommit: $options->autoCommit,
            consumerTimeout: $options->consumerTimeout
        );
    }
}
