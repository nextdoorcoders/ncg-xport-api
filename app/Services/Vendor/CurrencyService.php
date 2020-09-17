<?php

namespace App\Services\Vendor;

use App\Models\Geo\Location as LocationModel;
use App\Models\Vendor\Currency;
use App\Models\Vendor\Currency as CurrencyModel;
use App\Models\Vendor\CurrencyRate as CurrencyRateModel;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use DiDom\Query;
use Illuminate\Database\Eloquent\Collection as DatabaseCollection;
use Illuminate\Support\Collection;

class CurrencyService
{
    /**
     * @return DatabaseCollection
     */
    public function allCurrencies()
    {
        /** @var DatabaseCollection $currencies */
        $currencies = Currency::query()
            ->get();

        return $currencies;
    }

    /**
     * Получение обменного курса
     *
     * @return Collection
     * @throws \DiDom\Exceptions\InvalidSelectorException
     */
    public function getMinfinExchangeAndNationalRates(): Collection
    {
        $document = new Document('https://minfin.com.ua/currency/banks/', true);

        $table = $document->first('table.table-response.mfm-table.mfcur-table-lg-banks.mfcur-table-lg');

        $rates = collect();

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
                    $data['rates'][] = preg_replace('/\n/', '', $col->text());
                }
            }

            [$purchase, $sale] = explode('/', $data['rates'][0]);

            $data[CurrencyRateModel::TYPE_EXCHANGE_RATE] = (object)[
                'purchase' => round((float)$purchase, 4),
                'average'  => round((float)($purchase + $sale) / 2, 4),
                'sale'     => round((float)$sale, 4),
            ];

            $data[CurrencyRateModel::TYPE_NATIONAL_RATE] = (object)[
                'average' => round((float)$data['rates'][1], 4),
            ];

            unset($data['rates']);

            $rates->push((object)$data);
        }

        return $rates;
    }

    /**
     * Получение курса на межбанке
     *
     * @return Collection
     * @throws \DiDom\Exceptions\InvalidSelectorException
     */
    public function getMinfinInterbankRates(): Collection
    {
        $document = new Document('https://minfin.com.ua/currency/mb/', true);

        $table = $document->first('table.mb-table-currency');

        $rates = collect();

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
            $rates->push((object)[
                'from_currency'                        => $value[0],
                'to_currency'                          => 'UAH',
                CurrencyRateModel::TYPE_INTERBANK_RATE => (object)[
                    'purchase' => round((float)$value[1], 4),
                    'average'  => round((float)($value[1] + $value[2]) / 2, 4),
                    'sale'     => round((float)$value[2], 4),
                ],
            ]);
        }

        return $rates;
    }

    public function getMinfinCurrencyRates()
    {
        try {
            /** @var LocationModel $location */
            $location = LocationModel::query()
                ->whereJsonContains('parameters', ['alpha2' => 'UA'])
                ->first();

            $currencies = CurrencyModel::query()
                ->get();

            $rates1 = $this->getMinfinExchangeAndNationalRates();
            $rates1->each(function ($item) use ($location, $currencies) {
                /** @var CurrencyModel $fromCurrency */
                $fromCurrency = $currencies->where('code', $item->from_currency)
                    ->first();

                /** @var CurrencyModel $toCurrency */
                $toCurrency = $currencies->where('code', $item->to_currency)
                    ->first();

                if ($fromCurrency && $toCurrency) {
                    // Forward rate
                    /** @var CurrencyRateModel $rate */
                    $rate = CurrencyRateModel::query()
                        ->create([
                            'from_currency_id' => $fromCurrency->id,
                            'to_currency_id'   => $toCurrency->id,
                            'location_id'      => $location->id,
                            'source'           => CurrencyRateModel::SOURCE_MINFIN,
                            'type'             => CurrencyRateModel::TYPE_EXCHANGE_RATE,
                            'rate'             => $item->exchange,
                        ]);

                    // Back rate
                    $rate->createBackRate();


                    $rate = CurrencyRateModel::query()
                        ->create([
                            'from_currency_id' => $fromCurrency->id,
                            'to_currency_id'   => $toCurrency->id,
                            'location_id'      => $location->id,
                            'source'           => CurrencyRateModel::SOURCE_MINFIN,
                            'type'             => CurrencyRateModel::TYPE_NATIONAL_RATE,
                            'rate'             => $item->national,
                        ]);

                    $rate->createBackRate();
                }
            });

            $rates2 = $this->getMinfinInterbankRates();
            $rates2->each(function ($item) use ($location, $currencies) {
                /** @var CurrencyModel $fromCurrency */
                $fromCurrency = $currencies->where('code', $item->from_currency)
                    ->first();

                /** @var CurrencyModel $toCurrency */
                $toCurrency = $currencies->where('code', $item->to_currency)
                    ->first();

                if ($fromCurrency && $toCurrency) {
                    // Forward rate
                    /** @var CurrencyRateModel $rate */
                    $rate = CurrencyRateModel::query()
                        ->create([
                            'from_currency_id' => $fromCurrency->id,
                            'to_currency_id'   => $toCurrency->id,
                            'location_id'      => $location->id,
                            'source'           => CurrencyRateModel::SOURCE_MINFIN,
                            'type'             => CurrencyRateModel::TYPE_INTERBANK_RATE,
                            'rate'             => $item->interbank,
                        ]);

                    // Back rate
                    $rate->createBackRate();
                }
            });

            /** @var CurrencyModel $uahCurrency */
            $uahCurrency = CurrencyModel::query()
                ->where('code', 'UAH')
                ->first();

            $rates = CurrencyRateModel::query()
                ->with([
                    'fromCurrency',
                    'toCurrency',
                ])
                ->where('to_currency_id', $uahCurrency->id)
                ->where('source', CurrencyRateModel::SOURCE_MINFIN)
                ->get()
                ->groupBy('type');

            $rates->each(function ($group) {
                $group->each(function (CurrencyRateModel $from) use ($group) {
                    $group->each(function (CurrencyRateModel $to) use ($from) {
                        if ($from->type === CurrencyRateModel::TYPE_NATIONAL_RATE) {
                            $rate = [
                                'average' => round((float)$from->rate['average'] / $to->rate['average'], 4),
                            ];
                        } else {
                            $rate = [
                                'purchase' => round((float)$from->rate['purchase'] / $to->rate['purchase'], 4),
                                'average'  => round((float)$from->rate['average'] / $to->rate['average'], 4),
                                'sale'     => round((float)$from->rate['sale'] / $to->rate['sale'], 4),
                            ];
                        }

                        // Cross rate
                        CurrencyRateModel::query()
                            ->create([
                                'from_currency_id' => $from->from_currency_id,
                                'to_currency_id'   => $to->from_currency_id,
                                'location_id'      => $from->location_id,
                                'source'           => $from->source,
                                'type'             => $from->type,
                                'rate'             => $rate,
                            ]);
                    });
                });
            });
        } catch (InvalidSelectorException $e) {

        }
    }

    public function updateCurrency()
    {
        $this->getMinfinCurrencyRates();
    }
}
