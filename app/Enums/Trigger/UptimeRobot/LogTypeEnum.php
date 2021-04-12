<?php

namespace App\Enums\Trigger\UptimeRobot;

use BenSampo\Enum\Enum;

final class LogTypeEnum extends Enum
{
    const down = 1;
    const up = 2;
    const paused = 99;
    const started = 98;
}
