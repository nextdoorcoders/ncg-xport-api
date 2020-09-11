<?php

namespace App\Services\Google\AdWords;

use App\Exceptions\MessageException;
use App\Models\Marketing\Campaign as CampaignModel;
use App\Models\Marketing\Project as ProjectModel;
use App\Repositories\Google\AdWords\CampaignRepository;
use Illuminate\Support\Collection;

class CampaignService
{
    protected CampaignRepository $campaignRepository;

    /**
     * CampaignService constructor.
     *
     * @param CampaignRepository $campaignRepository
     */
    public function __construct(CampaignRepository $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * @param ProjectModel $project
     * @return Collection
     * @throws MessageException
     */
    public function allCampaigns(ProjectModel $project)
    {
        $this->campaignRepository->setAccount($project);

        $campaigns = $this->campaignRepository->paginate();

        $existedCampaigns = CampaignModel::query()
            ->whereIn('campaign_id', $campaigns->pluck('id'))
            ->get()
            ->pluck('id', 'campaign_id')
            ->toArray();

        $campaigns->each(function ($campaign) use ($existedCampaigns) {
            $campaign->campaign_imported = array_key_exists($campaign->id, $existedCampaigns);

            if ($campaign->campaign_imported === true) {
                $campaign->xport_campaign_id = $existedCampaigns[$campaign->id];
            }
        });

        return $campaigns;
    }

    /**
     * @param CampaignModel $campaign
     * @param string        $status
     * @throws MessageException
     */
    public function updateCampaignStatus(CampaignModel $campaign, string $status)
    {
        $this->campaignRepository->setAccount($campaign->project);

        $this->campaignRepository->update($campaign, $status);
    }
}
