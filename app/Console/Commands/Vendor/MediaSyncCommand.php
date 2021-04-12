<?php

namespace App\Console\Commands\Vendor;

use App\Services\Vendor\MediaSyncService;
use Illuminate\Console\Command;

class MediaSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendor:media-sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update information about each media sync';

    private MediaSyncService $mediaSync;

    /**
     * Create a new command instance.
     *
     * @param MediaSyncService $mediaSync
     */
    public function __construct(MediaSyncService $mediaSync)
    {
        parent::__construct();

        $this->mediaSync = $mediaSync;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->mediaSync->updateMediaSync();
    }
}
