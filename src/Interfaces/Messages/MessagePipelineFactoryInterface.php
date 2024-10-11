<?php

namespace Micromus\KafkaBus\Interfaces\Messages;

interface MessagePipelineFactoryInterface
{
    public function create(array $middlewares): MessagePipelineInterface;
}
