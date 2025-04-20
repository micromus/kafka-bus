<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Exceptions\Listeners;

use LogicException;

final class CannotCommitOffsetException extends LogicException
{
}
