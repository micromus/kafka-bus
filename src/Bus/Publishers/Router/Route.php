<?php

namespace Micromus\KafkaBus\Bus\Publishers\Router;

use Micromus\KafkaBus\Interfaces\Producers\Messages\ProducerMessageInterface;
use Micromus\KafkaBus\Topics\Topic;

/**
 * @template TMessage of ProducerMessageInterface = mixed
 */
readonly class Route
{
    /**
     * @param class-string<TMessage> $messageClass
     * @param Topic $topic
     * @param Options $options
     */

    public function __construct(
        public string $messageClass,
        public Topic $topic,
        public Options $options = new Options()
    ) {
    }
}
