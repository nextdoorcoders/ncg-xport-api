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

        $dayOfWeek = $condition->parameters->day_of_week ?? null;

        /**
         * Check day of week
         */

        if ($dayOfWeek != null && $dayOfWeek != $current->dayOfWeek) {
            return false;
        }

        /**
         * Check dates
         */

        /*
         * Получаем текущие дату и время часового пояса. Устанавливаем дату и время
         * из настроек триггера. Если дата не указана - берем текущую. Если время
         * не указано - берем начало или конец дня в зависимости от поля настройки
         */

        $dateStartAt = $condition->parameters->date_start_at ?? now();
        $timeStartAt = $condition->parameters->time_start_at ?? now()->startOfDay();

        $dateTimeStart = Carbon::now($timezone);
        $dateTimeStart->setDateFrom(Carbon::parse($dateStartAt));
        $dateTimeStart->setTimeFrom(Carbon::parse($timeStartAt));

        $dateEndAt = $condition->parameters->date_end_at ?? now();
        $timeEndAt = $condition->parameters->time_end_at ?? now()->endOfDay();

        $dateTimeEnd = Carbon::now($timezone);
        $dateTimeEnd->setDateFrom(Carbon::parse($dateEndAt));
        $dateTimeEnd->setTimeFrom(Carbon::parse($timeEndAt));

        if ($dateTimeStart->lessThanOrEqualTo($current) && $dateTimeEnd->greaterThanOrEqualTo($current)) {
            return true;
        }

        return false;
    }

    /**
     * @param ConditionModel $condition
     * @return \Illuminate\Support\Carbon
     */
    public function currentCalendar(ConditionModel $condition)
    {
        return now($condition->parameters->timezone ?? 'UTC');
    }
}
