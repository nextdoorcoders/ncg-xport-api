<?php

namespace App\Services\Vendor;

use App\Models\Geo\Location as LocationModel;
use App\Models\Trigger\Vendor;
use App\Models\Trigger\VendorLocation;
use App\Models\Trigger\VendorType as VendorTypeModel;
use App\Models\Vendor\Currency as CurrencyModel;
use App\Models\Vendor\CurrencyRate as CurrencyRateModel;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use DiDom\Query;
use Exception;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Support\Collection;

class CurrencyService
{
    const SOURCE_MINFIN = 'minfin';

    const VALUE_EXCHANGE = 'exchange';
    const VALUE_INTERBANK = 'interbank';
    const VALUE_NATIONAL = 'national';

    const VALUES = [
        self::VALUE_EXCHANGE,
        self::VALUE_INTERBANK,
        self::VALUE_NATIONAL,
    ];

    protected string $exchangeAndNationalRates = 'https://minfin.com.ua/currency/banks/';

    protected string $interbankRates = 'https://minfin.com.ua/currency/mb/';

    /**
     * @return DatabaseCollection
     */
    public function allCurrencies()
    {
        /** @var DatabaseCollection $currencies */
        $currencies = CurrencyModel::query()
            ->get();

        return $currencies;
    }

    /**
     * Получение обменного курса
     *
     * @return Collection
     * @throws InvalidSelectorException
     * @throws Exception
     */
    public function getMinfinExchangeAndNationalRates(): Collection
    {
        $document = new Document($this->exchangeAndNationalRates, true);

        $table = $document->first('table.table-response.mfm-table.mfcur-table-lg-banks.mfcur-table-lg');

        $values = collect();

        $rows = $table->find('//tbody//tr', Query::TYPE_XPATH);

        foreach ($rows as $rowIndex => $row) {
            $row = new Document($row->html());

            foreach ($row->find('.mfm-posr') as $item) {
                $item->setInnerHtml('/');
            }

            foreach ($row->find('.mfcur-nbu-full') as $item) {
                $item->remove();
            }

            $cols = $row->find('//td', Query::TYPE_XPATH);

            $cols = array_slice($cols, 0, 3);

            $data = [];

            foreach ($cols as $col) {
                $col = new Document($col->html());

                $link = $col->first('a[href*=banks]');

                if ($link) {
                    $href = $link->attr('href');
                    $data['from_currency'] = mb_strtoupper(last(explode('/', trim($href, '/'))));
                    $data['to_currency'] = 'UAH';
                } else {
                    $data['values'][] = preg_replace('/\n/', '', $col->text());
                }
            }

            [$purchase, $sale] = explode('/', $data['values'][0]);

            $purchase = (float)$purchase;
            $sale = (float)$sale;

            try {
                $data[self::VALUE_EXCHANGE] = (object)[
                    'purchase' => round($purchase, 4),
                    'average'  => round(($purchase + $sale) / 2, 4),
                    'sale'     => round($sale, 4),
                ];
            } catch (Exception $exception) {
                throw $exception;
            }

            $data[self::VALUE_NATIONAL] = (object)[
                'average' => round((float)$data['values'][1], 4),
            ];

            unset($data['values']);

            $values->push((object)$data);
        }

        return $values;
    }

    /**
     * Получение курса на межбанке
     *
     * @return Collection
     * @throws InvalidSelectorException
     */
    public function getMinfinInterbankRates(): Collection
    {
        $document = new Document($this->interbankRates, true);

        $table = $document->first('table.mb-table-currency');

        $values = collect();

        $rows = $table->find('//tbody//tr', Query::TYPE_XPATH);

        $data = [];

        array_push($data, [
            'USD',
            'EUR',
            'RUB',
        ]);

        foreach ($rows as $rowIndex => $row) {
            $row = new Document($row->html());

            foreach ($row->find('.mb-table-currency--trend') as $item) {
                $item->remove();
            }

            $cols = $row->find('//td', Query::TYPE_XPATH);

            $cols = array_slice($cols, 1, count($cols));

            foreach ($cols as $col) {
                $col = new Document($col->html());

                $data[$rowIndex + 1][] = trim(preg_replace('/\n/', '', $col->text()));
            }
        }

        $data = array_map(null, ...$data);

        foreach ($data as $key => $value) {
            $values->push((object)[
                'from_currency'       => $value[0],
                'to_currency'         => 'UAH',
                self::VALUE_INTERBANK => (object)[
                    'purchase' => round((float)$value[1], 4),
                    'average'  => round((float)($value[1] + $value[2]) / 2, 4),
                    'sale'     => round((float)$value[2], 4),
                ],
            ]);
        }

        return $values;
    }

    /**
     * @param Collection $mergedValues
     */
    private function processCurrencyRate(Collection $mergedValues)
    {
        $locations = LocationModel::query()
            ->where('type', LocationModel::TYPE_COUNTRY)
            ->whereJsonContains('parameters', ['alpha2' => 'UA']) // TODO: Remove in future
            ->get();

        $vendorsTypes = VendorTypeModel::query()
            ->whereHas('vendor', function ($vendor) {
                $vendor->where('type', Vendor::TYPE_CURRENCY)
                    ->where('source', self::SOURCE_MINFIN);
            })
            ->get();

        $currencies = CurrencyModel::query()
            ->get();

        $locations->each(function (LocationModel $location) use ($vendorsTypes, $currencies, $mergedValues) {
            $location->vendorsTypes()->sync($vendorsTypes);
            $location->load('vendorsTypes');

            $mergedValues->each(function ($rate) use ($location, $currencies) {
                // Each currency rate, one by one

                /** @var CurrencyModel $fromCurrency */
                $fromCurrency = $currencies->where('code', $rate->from_currency)
                    ->first();

                /** @var CurrencyModel $toCurrency */
                $toCurrency = $currencies->where('code', $rate->to_currency)
                    ->first();

                if ($fromCurrency && $toCurrency) {
                    foreach (self::VALUES as $valueType) {
                        $value = null;

                        switch ($valueType) {
                            case self::VALUE_EXCHANGE:
                                $value = $rate->exchange ?? null;
                                break;
                            case self::VALUE_INTERBANK:
                                $value = $rate->interbank ?? null;
                                break;
                            case self::VALUE_NATIONAL:
                                $value = $rate->national ?? null;
                                break;
                        }

                        if (!is_null($value)) {
                            /** @var VendorTypeModel $vendorType */
                            $vendorType = $location->vendorsTypes()
                                ->where('type', $valueType)
                                ->first();

                            /** @var VendorLocation $vendorLocation */
                            $vendorLocation = $vendorType->pivot;

                            // Forward value
                            /** @var CurrencyRateModel $currencyRate */
                            $currencyRate = CurrencyRateModel::query()
                                ->create([
                                    'vendor_type_id'     => $vendorType->id,
                                    'vendor_location_id' => $vendorLocation->id,
                                    'from_currency_id'   => $fromCurrency->id,
                                    'to_currency_id'     => $toCurrency->id,
                                    'value'              => $value,
                                ]);

                            // Back value
                            $currencyRate->createBackRate();
                        }
                    }
                }
            });
        });
    }

    private function processCrossRate()
    {
        $rates = CurrencyRateModel::query()
            ->whereHas('toCurrency', function ($toCurrency) {
                $toCurrency->where('code', 'UAH');
            })
            ->whereHas('vendorType', function ($vendorType) {
                $vendorType->whereHas('vendor', function ($vendor) {
                    $vendor->where([
                        'type'   => Vendor::TYPE_CURRENCY,
                        'source' => self::SOURCE_MINFIN,
                    ]);
                });
            })
            ->get();

        $groups = $rates->groupBy('vendorType.type');

        $groups->each(function ($group, $type) {
            $group->each(function (CurrencyRateModel $from) use ($group, $type) {
                $group->each(function (CurrencyRateModel $to) use ($from, $type) {
                    try {
                        if ($type === self::VALUE_NATIONAL) {
                            $value = [
                                'average' => round($from->value['average'] / $to->value['average'], 4),
                            ];
                        } else {
                            $value = [
                                'purchase' => round($from->value['purchase'] / $to->value['purchase'], 4),
                                'average'  => round($from->value['average'] / $to->value['average'], 4),
                                'sale'     => round($from->value['sale'] / $to->value['sale'], 4),
                            ];
                        }

                        // Cross value
                        CurrencyRateModel::query()
                            ->create([
                                'vendor_type_id'     => $from->vendor_type_id,
                                'vendor_location_id' => $from->vendor_location_id,
                                'from_currency_id'   => $from->from_currency_id,
                                'to_currency_id'     => $to->from_currency_id,
                                'value'              => $value,
                            ]);
                    } catch (Exception $exception) {
                        throw $exception;
                    }
                });
            });
        });
    }

    /**
     * @throws InvalidSelectorException
     */
    public function updateCurrency()
    {
        try {
            /*
             * Request currency rates
             */
            $values1 = $this->getMinfinExchangeAndNationalRates();
            $values2 = $this->getMinfinInterbankRates();

            $mergedValues = $values1->map(function ($value1) use ($values2) {
                $value2 = $values2->where('from_currency', $value1->from_currency)
                    ->where('to_currency', $value1->to_currency)
                    ->first();

                if ($value2) {
                    return (object)array_merge((array)$value1, (array)$value2);
                }

                return $value1;
            });

            /*
             * Processing currency rates
             */
            $this->processCurrencyRate($mergedValues);

            /*
             * Calculate cross rate
             */
            $this->processCrossRate();
        } catch (InvalidSelectorException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
