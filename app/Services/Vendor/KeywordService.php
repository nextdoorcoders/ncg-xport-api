<?php namespace App\Services\Vendor;

use App\Jobs\Vendor\UpdateKeywords;
use App\Models\Geo\Location as LocationModel;
use App\Models\Trigger\Condition as ConditionModel;
use App\Models\Trigger\Vendor;
use App\Models\Trigger\VendorType as VendorTypeModel;
use App\Models\Vendor\Keyword as KeywordModel;
use App\Models\Vendor\KeywordRate as KeywordRateModel;
use App\Models\VendorState;
use App\Services\Logs\VendorLogService;
use Carbon\Carbon;
use Exception;
use GSoares\GoogleTrends\Error\GoogleTrendsException;
use GSoares\GoogleTrends\Search\RelatedQueriesSearch;
use GSoares\GoogleTrends\Search\SearchFilter;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class KeywordService
{
    const STAT_CLASS_NAME = 'Keywords';
    const SOURCE_GOOGLE_TRENDS = 'google_trends';

    /**
     * @throws GoogleTrendsException
     */
    public function updateKeyword()
    {
        $locations = LocationModel::query()
            ->where('type', LocationModel::TYPE_COUNTRY)
            ->get();

        $vendorTypes = VendorTypeModel::query()
            ->whereHas('vendor', function ($vendor) {
                $vendor->where('type', Vendor::TYPE_KEYWORD)
                    ->where('source', self::SOURCE_GOOGLE_TRENDS);
            })
            ->get();

        $locations->each(function (LocationModel $location) use ($vendorTypes) {
            $location->vendorsTypes()->sync($vendorTypes, false);
        });

        /** @var Collection $conditions */
        $conditions = ConditionModel::query()
            ->whereHas('vendorType', function ($vendorTypes) {
                $vendorTypes->whereIn('type', [
                    'checkRank',
                    'compareRank',
                ]);
            })
            ->get();

        $conditions->each(function (ConditionModel $condition) {
            UpdateKeywords::dispatch($condition);
        });
    }

    /**
     * @param ConditionModel $condition
     * @param bool           $forceUpdate
     * @throws GoogleTrendsException
     */
    public function processingCondition(ConditionModel $condition, bool $forceUpdate = false)
    {
        $condition->loadMissing('vendorType');

        try {
            switch ($condition->vendorType->type) {
                case 'checkRank':
                    $this->processingKeyword($condition, 'keyword', $forceUpdate);
                    break;
                case 'compareRank':
                    $this->processingKeyword($condition, 'keyword', $forceUpdate);
                    $this->processingKeyword($condition, 'reference_keyword', $forceUpdate);
                    break;
            }
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param ConditionModel $condition
     * @param string         $parameterKey
     * @param bool           $forceUpdate - Принудительное обновление
     * @return KeywordModel
     * @throws GoogleTrendsException
     */
    protected function processingKeyword(ConditionModel $condition, string $parameterKey, bool $forceUpdate = false)
    {
        $keyword = null;

        $parameters = $condition->parameters;

        $keywordValue = $parameters->{$parameterKey};
        $keywordCode = $parameters->{$parameterKey . '_code'};

        if ($keywordValue) {
            if ($keywordCode) {
                /** @var KeywordModel $keyword */
                $keyword = KeywordModel::query()
                    ->where('code', $keywordCode)
                    ->first();

                if ($keyword) {
                    if ($keyword->keyword !== $keywordValue) {
                        $keywordCode = Str::random(64);

                        $condition->parameters->{$parameterKey . '_code'} = $keywordCode;
                        $condition->save();

                        $keyword->fill([
                            'code'    => $keywordCode,
                            'keyword' => $keywordValue,
                        ]);
                        $keyword->save();

                        /*
                         * Если ключевое слово было обновлено - удаляем
                         * всю статистику по предыдущему ключевому слову
                         */
                        $keyword->rates()->delete();
                    }
                }
            }

            if (!$keyword) {
                $keywordCode = Str::random(64);

                $condition->parameters->{$parameterKey . '_code'} = $keywordCode;
                $condition->save();

                $keyword = KeywordModel::query()
                    ->create([
                        'code'    => $keywordCode,
                        'keyword' => $keywordValue,
                    ]);
            }

            /*
             * Обновляем статистику
             */
            if ($keyword->wasRecentlyCreated || $keyword->rates()->count() === 0 || $forceUpdate) {
                $condition->loadMissing('vendorLocation.location');
                $location = $condition->vendorLocation->location;
                $this->getStatistics($condition, $keyword, $location->parameters->alpha2 ?? 'US', $parameters->days_ago);
            }
        } else {
            $condition->parameters->{$parameterKey} = null;
            $condition->parameters->{$parameterKey . '_code'} = null;
            $condition->save();

            if ($keywordCode) {
                KeywordModel::query()
                    ->where('code', $keywordCode)
                    ->delete();
            }
        }

        return $keyword;
    }

    /**
     * Запрашиваем статистику у серверов Google
     *
     * @param ConditionModel $condition
     * @param KeywordModel   $keyword
     * @param string         $location
     * @param                $daysAgo
     * @return KeywordRateModel
     */
    protected function getStatistics(ConditionModel $condition, KeywordModel $keyword, string $location, $daysAgo): KeywordRateModel
    {
        $value = 0;

        try {
            $searchFilter = (new SearchFilter())
                ->withSearchTerm($keyword->keyword)
                ->withLocation($location)
                ->withinInterval(Carbon::now()->subDays($daysAgo)->toDateTimeImmutable(), Carbon::now()->toDateTimeImmutable())
                ->withTopMetrics()
                ->withRisingMetrics();

                $results = (new RelatedQueriesSearch())
                    ->search($searchFilter)
                    ->getResults();

            if ($results && count($results) > 0) {
                $value = $results[0]->getValue();
            } else {
                Log::info(sprintf('Empty Google Trends result for keyword `%s`', $keyword->keyword), [
                    'condition' => $condition,
                    'keyword'   => $keyword,
                    'interval'  => [
                        Carbon::now()->subDays($daysAgo)->toDateTimeImmutable(),
                        Carbon::now()->toDateTimeImmutable(),
                    ],
                ]);
            }
            VendorState::setActive(self::STAT_CLASS_NAME);
        } catch (Exception $exception) {
            VendorLogService::writeError($exception->getMessage(), self::STAT_CLASS_NAME, 0, [$condition, $keyword]);
            report($exception);
        }

        /** @var KeywordRateModel $rate */
        $rate = $keyword->rates()
            ->create([
                'vendor_type_id'     => $condition->vendor_type_id,
                'vendor_location_id' => $condition->vendor_location_id,
                'value'              => $value,
            ]);

        return $rate;
    }
}
