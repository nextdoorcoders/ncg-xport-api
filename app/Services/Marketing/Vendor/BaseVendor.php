<?php

namespace App\Services\Marketing\Vendor;

use App\Models\Marketing\Condition as ConditionModel;

abstract class BaseVendor implements VendorInterface
{
    /**
     * @param ConditionModel $condition
     * @return bool
     */
    public function run(ConditionModel $condition): bool
    {
        $parameters = (object)$condition->parameters;

        $current = $this->current($condition->vendorLocation->city_id);

        if (is_null($current)) {
            return false;
        }

        if ($parameters->min <= $current && $current <= $parameters->max) {
            return true;
        }

        return false;
    }
}
