<?php

namespace Micromus\KafkaBus\Interfaces\Consumers;

use Micromus\KafkaBus\Consumers\Messages\ConsumerMessage;
use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Testing\Exceptions\KafkaMessagesEndedException;

interface ConsumerInterface
{
    /**
     * @throws KafkaMessagesEndedException
     * @throws ConsumerException
     */
    public function getMessage(): ConsumerMessage;

    public function commit(ConsumerMessage $consumerMessage): void;
}
