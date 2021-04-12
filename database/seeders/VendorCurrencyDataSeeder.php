<?php

namespace Database\Seeders;

use App\Models\Vendor\Currency as CurrencyModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class VendorCurrencyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CurrencyModel::query()
            ->create([
                'code'          => 'USD',
                'symbol'        => '$',
                'symbol_before' => true,
            ]);

        CurrencyModel::query()
            ->create([
                'code'          => 'EUR',
                'symbol'        => '€',
                'symbol_before' => true,
            ]);

        CurrencyModel::query()
            ->create([
                'code'          => 'UAH',
                'symbol'        => '₴',
                'symbol_before' => true,
            ]);

        CurrencyModel::query()
            ->create([
                'code'          => 'RUB',
                'symbol'        => '₽',
                'symbol_before' => true,
            ]);

        CurrencyModel::query()
            ->create([
                'code' => 'PLN',
            ]);

        CurrencyModel::query()
            ->create([
                'code' => 'CHF',
            ]);

        CurrencyModel::query()
            ->create([
                'code' => 'GBP',
            ]);

        CurrencyModel::query()
            ->create([
                'code' => 'AUD',
            ]);

        CurrencyModel::query()
            ->create([
                'code' => 'BYN',
            ]);

        CurrencyModel::query()
            ->create([
                'code' => 'CAD',
            ]);

        CurrencyModel::query()
            ->create([
                'code' => 'CNY',
            ]);

        CurrencyModel::query()
            ->create([
                'code' => 'CZK',
            ]);

        CurrencyModel::query()
            ->create([
                'code' => 'DKK',
            ]);

        CurrencyModel::query()
            ->create([
                'code' => 'HUF',
            ]);

        CurrencyModel::query()
            ->create([
                'code' => 'ILS',
            ]);

        CurrencyModel::query()
            ->create([
                'code' => 'JPY',
            ]);

        CurrencyModel::query()
            ->create([
                'code' => 'NOK',
            ]);

        CurrencyModel::query()
            ->create([
                'code' => 'SEK',
            ]);

        Artisan::call('vendor:currency');
    }
}
