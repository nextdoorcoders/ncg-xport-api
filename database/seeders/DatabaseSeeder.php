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
        $this->call(LanguageDataSeeder::class);
        $this->call(TriggerDataSeeder::class);
        $this->call(GeoLocationDataSeeder::class);
        $this->call(VendorCurrencyDataSeeder::class);
        $this->call(VendorWeatherDataSeeder::class);
    }
}
