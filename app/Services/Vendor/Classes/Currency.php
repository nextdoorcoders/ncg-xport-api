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
        if (is_null($current)) {
            return false;
        }

        $parameters = $condition->parameters;

        if ($parameters->rate_min <= $current && $current <= $parameters->rate_max) {
            return true;
        }

        return false;
    }

    /**
     * @param ConditionModel $condition
     * @return CurrencyRate
     */
    protected function getCurrencyRate(ConditionModel $condition)
    {
        $condition->loadMissing('vendorLocation');

        $parameters = $condition->parameters;

        $formCurrencyId = $parameters->from_currency_id;
        $toCurrencyId = $parameters->to_currency_id;

        /** @var CurrencyRate $currencyRate */
        $currencyRate = CurrencyRate::query()
            ->with([
                'fromCurrency',
                'toCurrency',
            ])
            ->where('vendor_type_id', $condition->vendor_type_id)
            ->where('vendor_location_id', $condition->vendor_location_id)
            ->where('from_currency_id', $formCurrencyId)
            ->where('to_currency_id', $toCurrencyId)
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
        $currencyRate = $this->getCurrencyRate($condition);

        if (!$currencyRate) {
            return null;
        }

        // Set dynamic relations
        $condition->setRelation('fromCurrency', $currencyRate->fromCurrency);
        $condition->setRelation('toCurrency', $currencyRate->toCurrency);

        $parameters = $condition->parameters;

        $rate = $currencyRate->value;

        // ??????????????????????, ?????????????? ?????? ????????????????????????
        return $rate->{$parameters->rate_type} ?? null;
    }

    /**
     * @param ConditionModel $condition
     * @return mixed|null
     */
    public function currentExchange(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return mixed|null
     */
    public function currentNational(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return mixed|null
     */
    public function currentInterbank(ConditionModel $condition)
    {
        return $this->getValue($condition);
    }
}
