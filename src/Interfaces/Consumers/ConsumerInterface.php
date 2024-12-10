<?php

namespace Micromus\KafkaBus\Interfaces\Consumers;

use Micromus\KafkaBus\Exceptions\Consumers\ConsumerException;
use Micromus\KafkaBus\Interfaces\Consumers\Messages\ConsumerMessageInterface;
use Micromus\KafkaBus\Testing\Exceptions\KafkaMessagesEndedException;

interface ConsumerInterface
{
    /**
     * @throws KafkaMessagesEndedException
     * @throws ConsumerException
     */
    public function getMessage(): ConsumerMessageInterface;

    public function commit(ConsumerMessageInterface $consumerMessage): void;
}
