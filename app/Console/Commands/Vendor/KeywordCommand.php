<?php

namespace App\Console\Commands\Vendor;

use App\Services\Vendor\KeywordService;
use Illuminate\Console\Command;

class KeywordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'vendor:keyword';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update information about keyword rates';

    protected KeywordService $keywordService;

    /**
     * Create a new command instance.
     *
     * @param KeywordService $keywordService
     */
    public function __construct(KeywordService $keywordService)
    {
        parent::__construct();

        $this->keywordService = $keywordService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \GSoares\GoogleTrends\Error\GoogleTrendsException
     */
    public function handle()
    {
        $this->keywordService->updateKeyword();
    }
}
