<?php

namespace Micromus\KafkaBus\Connections;

class KafkaConnectionConfiguration
{
    final public const PRODUCER_OPTIONS = [
        'transactional.id',
        'transaction.timeout.ms',
        'enable.idempotence',
        'enable.gapless.guarantee',
        'queue.buffering.max.messages',
        'queue.buffering.max.kbytes',
        'queue.buffering.max.ms',
        'linger.ms',
        'message.send.max.retries',
        'retries',
        'retry.backoff.ms',
        'queue.buffering.backpressure.threshold',
        'compression.codec',
        'compression.type',
        'batch.num.messages',
        'batch.size',
        'delivery.report.only.error',
        'dr_cb',
        'dr_msg_cb',
        'sticky.partitioning.linger.ms',
    ];

    final public const CONSUMER_OPTIONS = [
        'partition.assignment.strategy',
        'session.timeout.ms',
        'heartbeat.interval.ms',
        'group.protocol.type',
        'coordinator.query.interval.ms',
        'max.poll.interval.ms',
        'enable.auto.commit',
        'auto.commit.interval.ms',
        'enable.auto.offset.store',
        'queued.min.messages',
        'queued.max.messages.kbytes',
        'fetch.wait.max.ms',
        'fetch.message.max.bytes',
        'max.partition.fetch.bytes',
        'fetch.max.bytes',
        'fetch.min.bytes',
        'fetch.error.backoff.ms',
        'offset.store.method',
        'isolation.level',
        'consume_cb',
        'rebalance_cb',
        'offset_commit_cb',
        'enable.partition.eof',
        'check.crcs',
        'allow.auto.create.topics',
        'auto.offset.reset',
        'group.id',
    ];

    public function __construct(
        protected array $options = []
    ) {
    }

    public function getProducerOptions(array $customOptions = []): array
    {
        $settings = [
            ...$this->options,
            ...$this->filterSettingsByKeys($customOptions, self::PRODUCER_OPTIONS),
        ];

        return $this->cleanupSettings($settings);
    }

    public function getConsumerOptions(array $customOptions = []): array
    {
        $settings = [
            ...$this->options,
            ...$this->filterSettingsByKeys($customOptions, self::CONSUMER_OPTIONS),
        ];

        return $this->cleanupSettings($settings);
    }

    private function filterSettingsByKeys(array $customOptions, array $allowKeys): array
    {
        return array_filter(
            $customOptions,
            fn (string $key) => in_array($key, $allowKeys, true),
            ARRAY_FILTER_USE_KEY
        );
    }

    private function cleanupSettings(array $settings = []): array
    {
        return array_filter(array_map($this->prepareSetting(...), $settings));
    }

    private function prepareSetting(mixed $value): mixed
    {
        if (is_null($value)) {
            return null;
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return $value;
    }
}
