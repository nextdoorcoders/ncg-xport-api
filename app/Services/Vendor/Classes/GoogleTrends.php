<?php

namespace App\Services\Vendor\Classes;

use App\Models\Trigger\Condition as ConditionModel;
use DateTimeImmutable;
use GSoares\GoogleTrends\Search\RelatedQueriesSearch;
use GSoares\GoogleTrends\Search\SearchFilter;

class GoogleTrends extends BaseVendor
{
    /**
     * @param ConditionModel $condition
     * @param null           $current
     * @return bool
     */
    public function check(ConditionModel $condition, $current = null): bool
    {
        return false;
    }

    /**
     * @param ConditionModel $condition
     * @return |null
     * @throws \GSoares\GoogleTrends\Error\GoogleTrendsException
     */
    public function calendar(ConditionModel $condition)
    {
        $searchFilter = (new SearchFilter())
            ->withSearchTerm('adidas')
            ->withLocation('UA')
            ->withinInterval(new DateTimeImmutable('2020-10-10'), new DateTimeImmutable('2020-11-10'))
            ->withTopMetrics()
            ->withRisingMetrics();

        $result = (new RelatedQueriesSearch())
            ->search($searchFilter);

        return null;
    }
}
