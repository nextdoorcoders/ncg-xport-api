<?php

namespace App\Console\Commands\Vendor;

use App\Services\Geo\LocationService;
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

    protected LocationService $locationService;

    /**
     * Create a new command instance.
     *
     * @param LocationService $locationService
     */
    public function __construct(LocationService $locationService)
    {
        parent::__construct();

        $this->locationService = $locationService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $this->locationService->updateWeatherInformation();
    }
}
