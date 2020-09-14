<?php

namespace App\Services\Vendor\Classes;

use App\Models\Trigger\Condition as ConditionModel;

abstract class BaseVendor
{
    /**
     * @param ConditionModel $condition
     * @return mixed
     */
    public static function getCurrentValue(ConditionModel $condition)
    {
        $condition->loadMissing([
            'vendor',
            'vendorLocation',
        ]);

        return app()->call([
            $condition->vendor->callback,
            $condition->vendor->type
        ], [
            'condition' => $condition,
        ]);
    }

    /**
     * @param ConditionModel $condition
     * @return mixed
     */
    public static function checkCondition(ConditionModel $condition): bool
    {
        $condition->loadMissing([
            'vendor',
            'vendorLocation',
        ]);

        $currentValue = self::getCurrentValue($condition);

        return app()->call([
            $condition->vendor->callback,
            'check'
        ], [
            'condition' => $condition,
            'current' => $currentValue
        ]);
    }
}
