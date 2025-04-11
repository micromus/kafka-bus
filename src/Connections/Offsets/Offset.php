<?php

declare(strict_types=1);

namespace Micromus\KafkaBus\Connections\Offsets;

enum Offset
{
    case Early;
    case Latest;
}
