<?php

namespace App\Enums\Trigger\UptimeRobot;

use BenSampo\Enum\Enum;

final class AlertContactStatusEnum extends Enum
{
    const not_active = 0;
    const paused = 1;
    const active = 2;
}
