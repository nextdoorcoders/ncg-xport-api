<?php

namespace App\Jobs\Google;

use App\Models\Marketing\Campaign as CampaignModel;
use App\Repositories\Google\AdWords\CampaignRepository;
use Carbon\Carbon;
use Google\AdsApi\AdWords\v201809\cm\CampaignStatus;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCampaignStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public CampaignModel $campaign;

    public CampaignRepository $campaignRepository;

    /**
     * UpdateCampaignStatus constructor.
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
                    $status = CampaignStatus::ENABLED;
                } else {
                    $status = CampaignStatus::PAUSED;
                }

                if (
                    $googleCampaign->status !== $status &&
                    Carbon::parse($googleCampaign->start_date) <= now() &&
                    now() <= Carbon::parse($googleCampaign->end_date)
                ) {
                    $this->campaignRepository->update($campaign, $status);
                }
            }
        }
    }
}
