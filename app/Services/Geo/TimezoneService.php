<?php

namespace App\Services\Geo;

use DateTimeZone;
use Illuminate\Support\Collection;

class TimezoneService
{
    /**
     * @return Collection
     */
    public function allTimezones(): Collection
    {
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);

        return collect($timezones)
            ->map(function ($timezone) {
                return [
                    'code' => $timezone,
                ];
            });
    }
}
