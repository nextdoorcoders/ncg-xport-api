<?php

namespace App\Enums\Trigger\UptimeRobot;

use BenSampo\Enum\Enum;

final class HttpMethodEnum extends Enum
{
    const head = 1;
    const get = 2;
    const post = 3;
    const put = 4;
    const patch = 5;
    const delete = 6;
    const options = 7;
}
