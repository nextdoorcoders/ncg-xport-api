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
        $parameters = (object)$condition->parameters;

        if (is_null($current)) {
            return false;
        }

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
            ->where('location_id', $condition->vendorLocation->location_id)
            ->orderBy('created_at', 'desc')
            ->first();

        return $weather;
    }

    /**
     * @param ConditionModel $condition
     * @return int|null
     */
    public function temperature(ConditionModel $condition)
    {
        $weather = $this->getVendor($condition);

        if (!$weather) {
            return null;
        }

        return $weather->temperature;
    }

    /**
     * @param ConditionModel $condition
     * @return int|null
     */
    public function wind(ConditionModel $condition)
    {
        $weather = $this->getVendor($condition);

        if (!$weather) {
            return null;
        }

        return $weather->wind;
    }

    /**
     * @param ConditionModel $condition
     * @return int|null
     */
    public function pressure(ConditionModel $condition)
    {
        $weather = $this->getVendor($condition);

        if (!$weather) {
            return null;
        }

        return $weather->pressure;
    }

    /**
     * @param ConditionModel $condition
     * @return int|null
     */
    public function humidity(ConditionModel $condition)
    {
        $weather = $this->getVendor($condition);

        if (!$weather) {
            return null;
        }

        return $weather->humidity;
    }

    /**
     * @param ConditionModel $condition
     * @return int|null
     */
    public function clouds(ConditionModel $condition)
    {
        $weather = $this->getVendor($condition);

        if (!$weather) {
            return null;
        }

        return $weather->clouds;
    }

    /**
     * @param ConditionModel $condition
     * @return int|null
     */
    public function rain(ConditionModel $condition)
    {
        $weather = $this->getVendor($condition);

        if (!$weather) {
            return null;
        }

        return $weather->rain;
    }

    /**
     * @param ConditionModel $condition
     * @return int|null
     */
    public function snow(ConditionModel $condition)
    {
        $weather = $this->getVendor($condition);

        if (!$weather) {
            return null;
        }

        return $weather->snow;
    }
}
