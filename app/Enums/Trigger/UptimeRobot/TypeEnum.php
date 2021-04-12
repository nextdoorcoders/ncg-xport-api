<?php

namespace App\Enums\Trigger\UptimeRobot;

use BenSampo\Enum\Enum;

final class TypeEnum extends Enum
{
    const http = 1;
    const keyword = 2;
    const ping = 3;
    const port = 4;
    const heartbeat = 5;
}
