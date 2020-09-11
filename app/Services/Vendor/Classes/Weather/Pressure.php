<?php

namespace App\Services\Vendor\Classes\Weather;

use App\Models\Vendor\Weather;

class Pressure extends BaseWeather
{
    public function current($cityId)
    {
        /** @var Weather $weather */
        $weather = Weather::query()
            ->where('city_id', $cityId)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$weather) {
            return null;
        }

        return $weather->pressure;
    }
}
