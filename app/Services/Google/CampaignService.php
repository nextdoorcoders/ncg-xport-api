<?php

namespace App\Services\Google;

use App\Exceptions\MessageException;
use App\Jobs\Google\UpdateCampaignStatus;
use App\Models\Marketing\Campaign as CampaignModel;
use App\Models\Trigger\Map as MapModel;
use App\Repositories\Google\AdWords\CampaignRepository;
use Carbon\Carbon;
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
     * @param MapModel $map
     * @return array|Collection
     * @throws MessageException
     */
    public function allCampaigns(MapModel $map)
    {
        $project = $map->project;

        if ($project) {
            $this->campaignRepository->setAccount($project);

            $campaigns = $this->campaignRepository->paginate();

            $existedCampaigns = $map->campaigns()
                ->get()
                ->pluck('id', 'foreign_campaign_id')
                ->toArray();

            $campaigns->each(function ($campaign) use ($existedCampaigns) {
                $campaign->campaign_imported = array_key_exists($campaign->id, $existedCampaigns);
            });

            return $campaigns;
        }

        return [];
    }

    /**
     * @param CampaignModel $campaign
     */
    public function updateCampaignStatus(CampaignModel $campaign)
    {
        // Создаём Job для обновления статуса кампании
        UpdateCampaignStatus::dispatch($campaign);
    }
}
