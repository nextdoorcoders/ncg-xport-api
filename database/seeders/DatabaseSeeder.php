<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AccountLanguageDataSeeder::class);
        $this->call(GeoLocationDataSeeder::class);

        $this->call(AccessDataSeeder::class);
        $this->call(AccountUserDataSeeder::class);

        $this->call(TriggerVendorDataSeeder::class);
        $this->call(VendorCurrencyDataSeeder::class);
        $this->call(VendorKeywordDataSeeder::class);
        $this->call(VendorWeatherDataSeeder::class);
    }
}
