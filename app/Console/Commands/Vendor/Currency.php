<?php

namespace App\Console\Commands\Vendor;

use App\Services\Vendor\CurrencyService;
use Illuminate\Console\Command;

class Currency extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendor:currency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update information about current currency rates';

    protected CurrencyService $currencyService;

    /**
     * Create a new command instance.
     *
     * @param CurrencyService $currencyService
     */
    public function __construct(CurrencyService $currencyService)
    {
        parent::__construct();

        $this->currencyService = $currencyService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->currencyService->updateCurrency();
    }
}
