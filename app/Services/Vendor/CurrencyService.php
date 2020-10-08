<?php

namespace App\Services\Vendor;

use App\Models\Geo\Location as LocationModel;
use App\Models\Trigger\Vendor;
use App\Models\Trigger\VendorLocation;
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
                $data[CurrencyRateModel::TYPE_EXCHANGE_RATE] = (object)[
                    'purchase' => round($purchase, 4),
                    'average'  => round(($purchase + $sale) / 2, 4),
                    'sale'     => round($sale, 4),
                ];
            } catch (Exception $exception) {
                throw $exception;
            }

            $data[CurrencyRateModel::TYPE_NATIONAL_RATE] = (object)[
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
                'from_currency'                        => $value[0],
                'to_currency'                          => 'UAH',
                CurrencyRateModel::TYPE_INTERBANK_RATE => (object)[
                    'purchase' => round((float)$value[1], 4),
                    'average'  => round((float)($value[1] + $value[2]) / 2, 4),
                    'sale'     => round((float)$value[2], 4),
                ],
            ]);
        }

        return $values;
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
            $locations = LocationModel::query()
                ->with([
                    'vendors' => function ($vendors) {
                        $vendors->where('vendor_type', Vendor::VENDOR_TYPE_CURRENCY);
                    },
                ])
                ->where('type', LocationModel::TYPE_COUNTRY)
                ->whereJsonContains('parameters', ['alpha2' => 'UA']) // TODO: Remove in future
                ->get();

            $vendors = Vendor::query()
                ->where('vendor_type', Vendor::VENDOR_TYPE_CURRENCY)
                ->get();

            $currencies = CurrencyModel::query()
                ->get();

            $locations->each(function (LocationModel $location) use ($vendors, $currencies, $mergedValues) {
                $location->vendors()->sync($vendors);
                $location->refresh();

                $mergedValues->each(function ($rate) use ($location, $currencies) {
                    // Each currency rate, one by one

                    /** @var CurrencyModel $fromCurrency */
                    $fromCurrency = $currencies->where('code', $rate->from_currency)
                        ->first();

                    /** @var CurrencyModel $toCurrency */
                    $toCurrency = $currencies->where('code', $rate->to_currency)
                        ->first();

                    if ($fromCurrency && $toCurrency) {
                        foreach (Vendor::CURRENCY_VALUES as $valueType) {
                            $value = null;

                            switch ($valueType) {
                                case Vendor::VALUE_TYPE_CURRENCY_EXCHANGE:
                                    $value = $rate->exchange ?? null;
                                    break;
                                case Vendor::VALUE_TYPE_CURRENCY_NATIONAL:
                                    $value = $rate->interbank ?? null;
                                    break;
                                case Vendor::VALUE_TYPE_CURRENCY_INTERBANK:
                                    $value = $rate->national ?? null;
                                    break;
                            }

                            if (!is_null($value)) {
                                /** @var Vendor $vendor */
                                $vendor = $location->vendors
                                    ->where('vendor_type', Vendor::VENDOR_TYPE_CURRENCY)
                                    ->where('value_type', $valueType)
                                    ->first();

                                /** @var VendorLocation $vendorLocation */
                                $vendorLocation = $vendor->pivot;

                                // Forward value
                                /** @var CurrencyRateModel $value */
                                $value = $vendorLocation->currencies()
                                    ->create([
                                        'from_currency_id' => $fromCurrency->id,
                                        'to_currency_id'   => $toCurrency->id,
                                        'source'           => CurrencyRateModel::SOURCE_MINFIN,
                                        'type'             => $valueType,
                                        'value'            => $value,
                                    ]);

                                // Back value
                                $value->createBackRate();
                            }
                        }
                    }
                });
            });

            /*
             * Calculate cross rate
             */

            /** @var CurrencyModel $uahCurrency */
            $uahCurrency = CurrencyModel::query()
                ->where('code', 'UAH')
                ->first();

            $values = CurrencyRateModel::query()
                ->with([
                    'fromCurrency',
                    'toCurrency',
                ])
                ->where('to_currency_id', $uahCurrency->id)
                ->where('source', CurrencyRateModel::SOURCE_MINFIN)
                ->get()
                ->groupBy('type');

            $values->each(function ($group) {
                $group->each(function (CurrencyRateModel $from) use ($group) {
                    $group->each(function (CurrencyRateModel $to) use ($from) {
                        if ($from->type === CurrencyRateModel::TYPE_NATIONAL_RATE) {
                            $value = [
                                'average' => round((float)$from->value['average'] / $to->value['average'], 4),
                            ];
                        } else {
                            $value = [
                                'purchase' => round((float)$from->value['purchase'] / $to->value['purchase'], 4),
                                'average'  => round((float)$from->value['average'] / $to->value['average'], 4),
                                'sale'     => round((float)$from->value['sale'] / $to->value['sale'], 4),
                            ];
                        }

                        // Cross value
                        CurrencyRateModel::query()
                            ->create([
                                'vendor_location_id' => $from->vendor_location_id,
                                'from_currency_id'   => $from->from_currency_id,
                                'to_currency_id'     => $to->from_currency_id,
                                'source'             => $from->source,
                                'type'               => $from->type,
                                'value'              => $value,
                            ]);
                    });
                });
            });
        } catch (InvalidSelectorException $exception) {
            throw $exception;
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
