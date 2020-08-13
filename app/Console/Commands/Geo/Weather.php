<?php

namespace App\Console\Commands\Geo;

use App\Services\Geo\CityService;
use Illuminate\Console\Command;

class Weather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'geo:weather';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update information about current weather in each city';

    protected CityService $cityService;

    /**
     * Create a new command instance.
     *
     * @param CityService $cityService
     */
    public function __construct(CityService $cityService)
    {
        parent::__construct();

        $this->cityService = $cityService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->cityService->updateWeatherInformation();
    }
}
