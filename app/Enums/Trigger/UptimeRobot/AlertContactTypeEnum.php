<?php

namespace App\Enums\Trigger\UptimeRobot;

use BenSampo\Enum\Enum;

final class AlertContactTypeEnum extends Enum
{
    const sms = 1;
    const email = 2;
    const twitter = 3;
    const boxcar = 4;
    const webhook = 5;
    const push_bullet = 6;
    const zapier = 7;
    const pushover = 9;
    const hipchat= 10;
    const slack = 11;
}
