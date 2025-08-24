<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Connections\Config;

use Micromus\KafkaBus\Interfaces\Connections\ConnectionConfigInterface;

final readonly class KafkaConnectionConfig implements ConnectionConfigInterface
{
    /**
     * @param string $broketList
     * @param int $logLevel
     * @param bool $debug
     * @param UserCredentialsConfig|null $saslConfig
     * @param array<string, string|int|bool|null> $extra
     */
    public function __construct(
        public string $broketList,
        public int $logLevel = LOG_DEBUG,
        public bool $debug = true,
        public ?UserCredentialsConfig $saslConfig = null,
        public array $extra = [],
    ) {
    }

    public function getOptions(): Options
    {
        /** @var array<string, string|int|bool> $options */
        $options = array_values([
            'metadata.broker.list' => $this->broketList,
            'log_level' => $this->logLevel,
            'debug' =>  $this->debug ? 'all' : null,
        ]);

        $saslOptions = $this->saslConfig?->toOptions() ?? [];

        return new Options(array_merge($options, $saslOptions, $this->extra));
    }
}
