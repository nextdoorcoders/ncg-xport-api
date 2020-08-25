<?php

namespace App\Console\Commands\Vendor;

use App\Services\Geo\CityService;
use Exception;
use Illuminate\Console\Command;

class Weather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendor:weather';

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
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $this->cityService->updateWeatherInformation();
    }
}
