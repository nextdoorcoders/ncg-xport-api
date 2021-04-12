<?php

namespace App\Console\Commands\Vendor;

use Illuminate\Console\Command;

class UptimeRobotCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendor:uptime-robot';

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
        // Not implemented. Implemented webhooks
        return 0;
    }
}
