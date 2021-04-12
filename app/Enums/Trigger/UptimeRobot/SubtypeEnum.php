<?php

namespace App\Enums\Trigger\UptimeRobot;

use BenSampo\Enum\Enum;

final class SubtypeEnum extends Enum
{
    const http = 1;
    const https = 2;
    const ftp = 3;
    const smtp = 4;
    const pop3 = 5;
    const imap = 6;
    const custom = 99;
}
