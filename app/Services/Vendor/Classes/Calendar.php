<?php

namespace App\Services\Vendor\Classes;

use App\Models\Trigger\Condition as ConditionModel;

class Calendar extends BaseVendor
{
    public function check(ConditionModel $condition, string $current = null): bool
    {
        return false;
    }

    public function calendar(ConditionModel $condition)
    {
        return null;
    }
}
