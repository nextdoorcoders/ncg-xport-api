<?php

namespace App\Services\Trigger\Vendor\Classes;

use App\Models\Vendor\Weather;
use App\Services\Trigger\Vendor\BaseVendor;

class Rain extends BaseVendor
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

        return $weather->rain;
    }
}
