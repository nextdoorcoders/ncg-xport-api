<?php

namespace App\Enums\Trigger\UptimeRobot;

use BenSampo\Enum\Enum;

final class HttpAuthTypeEnum extends Enum
{
    const basic = 1;
    const digest = 2;
}
