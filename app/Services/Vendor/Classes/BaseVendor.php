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
            'vendorType',
            'vendorLocation',
        ]);

        return $this->{$condition->vendorType->type}($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return mixed
     */
    public function checkCondition(ConditionModel $condition): bool
    {
        $condition->loadMissing([
            'vendorType',
            'vendorLocation',
        ]);

        $currentValue = self::getCurrentValue($condition);

        $check = $this->check($condition, $currentValue);

        return $condition->is_inverted ? !$check : $check;
    }
}
