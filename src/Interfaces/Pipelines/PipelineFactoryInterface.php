<?php

namespace Micromus\KafkaBus\Interfaces\Pipelines;

interface PipelineFactoryInterface
{
    public function create(array $middlewares): PipelineInterface;
}
