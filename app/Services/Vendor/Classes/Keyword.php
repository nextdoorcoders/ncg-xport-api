<?php

namespace App\Services\Vendor\Classes;

use App\Models\Trigger\Condition as ConditionModel;
use App\Models\Vendor\Keyword as KeywordModel;
use App\Models\Vendor\KeywordRate as KeywordRateModel;
use App\Services\Vendor\KeywordService;

class Keyword extends BaseVendor
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

        $condition->loadMissing('vendorType');

        $parameters = $condition->parameters;

        if ($condition->vendorType->type === 'checkRank') {
            if ($parameters->min_rank <= $current && $current <= $parameters->max_rank) {
                return true;
            }
        } else if ($condition->vendorType->type === 'compareRank') {
            /** @var KeywordModel $referenceKeyword */
            $referenceKeyword = KeywordModel::query()
                ->with('rates')
                ->whereHas('rates')
                ->where('code', $parameters->reference_keyword_code)
                ->first();

            if ($referenceKeyword) {
                /** @var KeywordRateModel $rate */
                $rate = $referenceKeyword->rates()
                    ->orderBy('created_at', 'desc')
                    ->first();

                switch ($parameters->rate_type) {
                    case 'less':
                        return $current < $rate->value;
                    case 'equal':
                        return $current == $rate->value;
                    case 'greater':
                        return $current > $rate->value;
                }
            }
        }

        return false;
    }

    /**
     * @param ConditionModel $condition
     * @throws \GSoares\GoogleTrends\Error\GoogleTrendsException
     */
    public function checking(ConditionModel &$condition): void
    {
        /*
         * Перед проверкой условия - выполняем проверку наличия данных
         * для проверки условия, так как триггер не имеет возможности
         * подгрузить данные до момента их использования
         */

        /** @var KeywordService $keywordService */
        $keywordService = app(KeywordService::class);
        $keywordService->processingCondition($condition);
    }

    /**
     * @param ConditionModel $condition
     * @return string|null
     */
    public function currentCheckRank(ConditionModel $condition)
    {
        $keyword = null;
        $keywordCode = $condition->parameters->keyword_code;

        if ($keywordCode) {
            /** @var KeywordModel $keyword */
            $keyword = KeywordModel::query()
                ->with([
                    'rates' => function ($rates) {
                        $rates->latest()->first();
                    }
                ])
                ->whereHas('rates')
                ->where('code', $keywordCode)
                ->first();
        }

        if (!$keyword) {
            return null;
        }

        // Set dynamic relations
        $condition->setRelation('keyword', $keyword);

        /** @var KeywordRateModel $rate */
        $rate = $keyword->rates()
            ->orderBy('created_at', 'desc')
            ->first();

        if ($rate) {
            return $rate->value;
        }

        return null;
    }

    /**
     * @param ConditionModel $condition
     * @return string|null
     */
    public function currentCompareRank(ConditionModel $condition)
    {
        $keyword = null;
        $referenceKeyword = null;

        $parameters = $condition->parameters;
        $keywordCode = $parameters->keyword_code;
        $referenceKeywordCode = $parameters->reference_keyword_code;

        if ($keywordCode && $referenceKeywordCode) {
            /** @var KeywordModel $keyword */
            $keyword = KeywordModel::query()
                ->with([
                    'rates' => function ($rates) {
                        $rates->latest()->first();
                    }
                ])
                ->whereHas('rates')
                ->where('code', $keywordCode)
                ->first();

            /** @var KeywordModel $referenceKeyword */
            $referenceKeyword = KeywordModel::query()
                ->with([
                    'rates' => function ($rates) {
                        $rates->latest()->first();
                    }
                ])
                ->whereHas('rates')
                ->where('code', $referenceKeywordCode)
                ->first();
        }

        if (!$keyword || !$referenceKeyword) {
            return null;
        }

        // Set dynamic relations
        $condition->setRelation('keyword', $keyword);
        $condition->setRelation('referenceKeyword', $referenceKeyword);

        /** @var KeywordRateModel $rate */
        $rate = $keyword->rates()
            ->orderBy('created_at', 'desc')
            ->first();

        if ($rate) {
            return $rate->value;
        }

        return null;
    }
}
