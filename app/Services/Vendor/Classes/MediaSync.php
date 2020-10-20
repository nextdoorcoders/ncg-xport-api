<?php

namespace App\Services\Vendor\Classes;

use App\Models\Trigger\Condition as ConditionModel;
use Carbon\Carbon;

class MediaSync extends BaseVendor
{
    /**
     * @param ConditionModel $condition
     * @param Carbon|null    $current
     * @return bool
     */
    public function check(ConditionModel $condition, Carbon $current = null): bool
    {
        return false;
    }

    /**
     * @param ConditionModel $condition
     * @return \Illuminate\Support\Carbon
     */
    public function tv(ConditionModel $condition)
    {
        return null;
    }

    /**
     * @param ConditionModel $condition
     * @return \Illuminate\Support\Carbon
     */
    public function radio(ConditionModel $condition)
    {
        return null;
    }
}
