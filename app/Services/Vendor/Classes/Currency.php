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

        if ($parameters->min <= $current && $current <= $parameters->max) {
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
            ->where('location_id', $condition->vendorLocation->location_id)
            ->where('from_currency_id', $formCurrencyId)
            ->where('to_currency_id', $toCurrencyId)
            ->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->first();


        $currencyRateS = CurrencyRate::query()
            ->where('location_id', $condition->vendorLocation->location_id)
            ->where('from_currency_id', $formCurrencyId)
            ->where('to_currency_id', $toCurrencyId)
            ->where('type', $type)
            ->orderBy('created_at', 'desc')
            ->toSql();

        return $currencyRate;
    }

    public function currency_exchange(ConditionModel $condition) {
        $currencyRate = $this->getVendor($condition);

        if (!$currencyRate) {
            return null;
        }

        $parameters = $condition->parameters;

        $rateType = $parameters['rate_type'];
    }

    public function currency_national(ConditionModel $condition) {
        $currencyRate = $this->getVendor($condition);

        if (!$currencyRate) {
            return null;
        }
    }

    public function currency_interbank(ConditionModel $condition) {
        $currencyRate = $this->getVendor($condition);

        if (!$currencyRate) {
            return null;
        }
    }
}
