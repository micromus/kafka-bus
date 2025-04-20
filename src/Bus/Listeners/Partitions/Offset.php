<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Bus\Listeners\Partitions;

enum Offset
{
    case Early;
    case Latest;
}
