<?php

namespace Micromus\KafkaBus\Messages;

use Micromus\KafkaBus\Interfaces\Messages\MessagePipelineInterface;
use Micromus\KafkaBus\Interfaces\Messages\MessagePipelineFactoryInterface;

class MessagePipelineFactory implements MessagePipelineFactoryInterface
{
    public function create(array $middlewares): MessagePipelineInterface
    {
        return new MessagePipeline($middlewares);
    }
}
