<?php

namespace App\Console\Commands\Vendor;

use App\Models\Vendor\CurrencyRate as CurrencyRateModel;
use App\Models\Vendor\Weather as WeatherModel;
use Illuminate\Console\Command;

class Cleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendor:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        CurrencyRateModel::query()
            ->where('created_at', '<', now()->subDay())
            ->delete();

        WeatherModel::query()
            ->where('created_at', '<', now()->subDay())
            ->delete();
    }
}
