<?php

namespace App\Services\Vendor\Classes\Weather;

use App\Models\Trigger\Condition as ConditionModel;
use App\Services\Vendor\Classes\BaseVendor;

abstract class BaseWeather extends BaseVendor
{
    /**
     * @param ConditionModel $condition
     * @return bool
     */
    public function run(ConditionModel $condition): bool
    {
        $parameters = (object)$condition->parameters;

        $current = $this->current($condition->vendorLocation->location_id);

        if (is_null($current)) {
            return false;
        }

        if ($parameters->min <= $current && $current <= $parameters->max) {
            return true;
        }

        return false;
    }
}
