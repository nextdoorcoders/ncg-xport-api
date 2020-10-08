<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class VendorWeatherDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('vendor:weather');
    }
}
