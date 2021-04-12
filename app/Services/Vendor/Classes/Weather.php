<?php

namespace App\Services\Vendor\Classes;

use App\Models\Trigger\Condition as ConditionModel;
use App\Models\Vendor\Weather as WeatherModel;

class Weather extends BaseVendor
{
    /**
     * @param ConditionModel $condition
     * @param null           $current
     * @return bool
     */
    public function check(ConditionModel $condition, $current = null): bool
    {
        if (is_null($current)) {
            return false;
        }

        $parameters = $condition->parameters;

        if ($parameters->min <= $current && $current <= $parameters->max) {
            return true;
        }

        return false;
    }

    /**
     * @param ConditionModel $condition
     * @return WeatherModel
     */
    protected function getVendor(ConditionModel $condition)
    {
        /** @var WeatherModel $weather */
        $weather = WeatherModel::query()
            ->where('vendor_location_id', $condition->vendor_location_id)
            ->orderBy('created_at', 'desc')
            ->first();

        return $weather;
    }

    /**
     * @param ConditionModel $condition
     * @return string|null
     */
    protected function getValue(ConditionModel $condition)
    {
        $weather = $this->getVendor($condition);

        if (!$weather) {
            return null;
        }

        return $weather->value;
    }

    /**
     * @param ConditionModel $condition
     * @return int|null
     */
    public function currentTemperature(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return int|null
     */
    public function currentWind(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return int|null
     */
    public function currentPressure(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return int|null
     */
    public function currentHumidity(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return int|null
     */
    public function currentClouds(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return int|null
     */
    public function currentRain(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return int|null
     */
    public function currentSnow(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }
}
