<?php

namespace App\Jobs\Google;

use App\Models\Marketing\Campaign as CampaignModel;
use App\Repositories\Google\AdWords\CampaignRepository;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateCampaignStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public CampaignModel $campaign;

    public string $status;

    public CampaignRepository $campaignRepository;

    /**
     * UpdateCampaignStatus constructor.
     *
     * @param CampaignModel $campaign
     * @param string        $status
     */
    public function __construct(CampaignModel $campaign, string $status)
    {
        $this->campaign = $campaign;

        $this->status = $status;

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

            if ($googleCampaign && Carbon::parse($googleCampaign->start_date) <= now() && now() <= Carbon::parse($googleCampaign->end_date)) {
                $this->campaignRepository->update($campaign, $this->status);
            }
        }
    }
}
