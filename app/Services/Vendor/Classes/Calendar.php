<?php

namespace App\Services\Vendor\Classes;

use App\Models\Trigger\Condition as ConditionModel;
use Carbon\Carbon;

class Calendar extends BaseVendor
{
    /**
     * @param ConditionModel $condition
     * @param Carbon|null    $current
     * @return bool
     */
    public function check(ConditionModel $condition, Carbon $current = null): bool
    {
        $timezone = $current->timezone;

        $dayOfWeek = $condition->parameters['day_of_week'] ?? null;

        /*
         * Check day of week
         */

        if ($dayOfWeek != null && $dayOfWeek != $current->dayOfWeek) {
            return false;
        }

        /*
         * Check dates
         */

        $dateTimeStart = Carbon::now($timezone);
        $dateTimeStart->setDateFrom(Carbon::parse($condition->parameters['date_start_at']));
        $dateTimeStart->setTimeFrom(Carbon::parse($condition->parameters['time_start_at']));

        $dateTimeEnd = Carbon::now($timezone);
        $dateTimeEnd->setDateFrom(Carbon::parse($condition->parameters['date_end_at']));
        $dateTimeEnd->setTimeFrom(Carbon::parse($condition->parameters['time_end_at']));

        if ($dateTimeStart->greaterThan($current)) {
            return false;
        }

        if ($dateTimeEnd->lessThan($current)) {
            return false;
        }

        return true;
    }

    /**
     * @param ConditionModel $condition
     * @return \Illuminate\Support\Carbon
     */
    public function calendar(ConditionModel $condition)
    {
        return now($condition->parameters['timezone'] ?? 'UTC');
    }
}
