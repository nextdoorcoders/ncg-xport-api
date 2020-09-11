<?php

namespace App\Console\Commands\Vendor;

use App\Services\Vendor\WeatherService;
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

    protected WeatherService $weatherService;

    /**
     * Create a new command instance.
     *
     * @param WeatherService $weatherService
     */
    public function __construct(WeatherService $weatherService)
    {
        parent::__construct();

        $this->weatherService = $weatherService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws Exception
     */
    public function handle()
    {
        $this->weatherService->updateWeather();
    }
}
