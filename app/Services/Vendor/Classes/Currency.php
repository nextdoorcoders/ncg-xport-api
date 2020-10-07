<?php

namespace App\Services\Vendor\Classes;

use App\Models\Trigger\Condition as ConditionModel;
use App\Models\Vendor\CurrencyRate;

class Currency extends BaseVendor
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

        if ($parameters->rate_min <= $current && $current <= $parameters->rate_max) {
            return true;
        }

        return false;
    }

    /**
     * @param ConditionModel $condition
     * @return CurrencyRate
     */
    protected function getVendor(ConditionModel $condition)
    {
        $condition->loadMissing('vendorLocation');

        $parameters = $condition->parameters;

        $formCurrencyId = $parameters['from_currency_id'];
        $toCurrencyId = $parameters['to_currency_id'];
        $type = $parameters['type'];

        /** @var CurrencyRate $currencyRate */
        $currencyRate = CurrencyRate::query()
            ->where('vendor_location_id', $condition->vendor_location_id)
            ->where('from_currency_id', $formCurrencyId)
            ->where('to_currency_id', $toCurrencyId)
            ->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->first();

        return $currencyRate;
    }

    /**
     * @param ConditionModel $condition
     * @return mixed|null
     */
    protected function getValue(ConditionModel $condition)
    {
        $currencyRate = $this->getVendor($condition);

        if (!$currencyRate) {
            return null;
        }

        $parameters = $condition->parameters;

        $rate = $currencyRate->value;
        $rateType = $parameters['rate_type'];

        return $rate[$rateType] ?? null;
    }

    /**
     * @param ConditionModel $condition
     * @return mixed|null
     */
    public function exchange(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return mixed|null
     */
    public function national(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return mixed|null
     */
    public function interbank(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }
}
