<?php

namespace App\Services\Marketing\Vendor\Classes;

use App\Models\Vendor\Weather;
use App\Services\Marketing\Vendor\BaseVendor;

class Snow extends BaseVendor
{
    public function current($cityId)
    {
        /** @var Weather $weather */
        $weather = Weather::query()
            ->where('city_id', $cityId)
            ->first();

        if (!$weather) {
            return null;
        }

        return $weather->snow;
    }
}
