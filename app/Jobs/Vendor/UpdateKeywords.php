<?php

namespace App\Jobs\Vendor;

use App\Models\Trigger\Condition as ConditionModel;
use App\Services\Vendor\KeywordService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateKeywords implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ConditionModel $condition;

    private KeywordService $keywordService;

    /**
     * Create a new job instance.
     *
     * @param ConditionModel $condition
     */
    public function __construct(ConditionModel $condition)
    {
        $this->condition = $condition;

        $this->keywordService = app(KeywordService::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \GSoares\GoogleTrends\Error\GoogleTrendsException
     */
    public function handle()
    {
        $this->keywordService->processingCondition($this->condition, true);
    }
}
