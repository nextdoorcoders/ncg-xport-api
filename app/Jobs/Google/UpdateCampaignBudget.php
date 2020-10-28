<?php

namespace App\Jobs\Google;

use App\Models\Marketing\Campaign as CampaignModel;
use App\Repositories\Google\AdWords\CampaignRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCampaignBudget implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public CampaignModel $campaign;

    public CampaignRepository $campaignRepository;

    /**
     * Create a new job instance.
     *
     * @param CampaignModel $campaign
     */
    public function __construct(CampaignModel $campaign)
    {
        $this->campaign = $campaign;

        $this->campaignRepository = app(CampaignRepository::class);
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \App\Exceptions\MessageException
     */
    public function handle()
    {
        $campaign = $this->campaign;

        $map = $campaign->map;

        $project = $map->project;

        if ($project) {
            $this->campaignRepository->setAccount($project);

            $googleCampaign = $this->campaignRepository->find($campaign);

            if ($googleCampaign) {
                if ($campaign->is_enabled) {
                    $amount = 1000000;
                } else {
                    $amount = 1;
                }

                $this->campaignRepository->updateBudget($campaign, $amount);
            }
        }
    }
}
