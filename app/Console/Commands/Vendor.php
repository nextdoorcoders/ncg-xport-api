<?php

namespace App\Console\Commands;

use App\Services\Trigger\VendorService;
use Illuminate\Console\Command;

class Vendor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendor:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update vendor locations';

    protected VendorService $vendorService;

    /**
     * Create a new command instance.
     *
     * @param VendorService $vendorService
     */
    public function __construct(VendorService $vendorService)
    {
        parent::__construct();

        $this->vendorService = $vendorService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->vendorService->updateAllLocations();
    }
}
