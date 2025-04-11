<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Exceptions;

use Exception;

final class CannotSetOffsetForPartitionsException extends Exception
{
    public function __construct(string $message, \RdKafka\Exception $previous)
    {
        parent::__construct($message, 0, $previous);
    }
}
