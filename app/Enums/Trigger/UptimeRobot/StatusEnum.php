<?php

namespace App\Enums\Trigger\UptimeRobot;

use BenSampo\Enum\Enum;

final class StatusEnum extends Enum
{
    const paused = 0;
    const not_checked_yet = 1;
    const up = 2;
    const seems_down = 8;
    const down = 9;
}
