<?php

namespace App\Services\Vendor\Classes;

use App\Models\Trigger\Condition as ConditionModel;

abstract class BaseVendor
{
    /**
     * @param ConditionModel $condition
     * @return mixed
     */
    public function getCurrentValue(ConditionModel $condition)
    {
        $condition->loadMissing([
            'vendor',
            'vendorLocation',
        ]);

        $instance = app($condition->vendor->callback);

        return $instance->{$condition->vendor->type}($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return mixed
     */
    public function checkCondition(ConditionModel $condition): bool
    {
        $condition->loadMissing([
            'vendor',
            'vendorLocation',
        ]);

        $currentValue = self::getCurrentValue($condition);

        $instance = app($condition->vendor->callback);

        return $instance->check($condition, $currentValue);
    }
}
