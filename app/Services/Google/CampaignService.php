<?php

namespace App\Services\Google;

use App\Exceptions\MessageException;
use App\Jobs\Google\UpdateCampaignBudget;
use App\Jobs\Google\UpdateCampaignStatus;
use App\Models\Marketing\Campaign as CampaignModel;
use App\Models\Trigger\Map as MapModel;
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

            $importedCampaigns = CampaignModel::query()
                ->whereIn('foreign_campaign_id', $campaigns->pluck('id'))
                ->get();

            $attachedCampaigns = $map->campaigns()
                ->get()
                ->pluck('id', 'foreign_campaign_id');

            $campaigns->each(function ($campaign) use ($importedCampaigns, $attachedCampaigns) {
                $count = $importedCampaigns->where('foreign_campaign_id', $campaign->id)->count();
                $isCampaignAttached = array_key_exists($campaign->id, $attachedCampaigns->toArray());

                $campaign->campaign_count = $count;
                $campaign->is_campaign_attached = $isCampaignAttached;
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

    /**
     * @param CampaignModel $campaign
     */
    public function updateCampaignBudget(CampaignModel $campaign)
    {
        // Создаём Job для обновления бюджета кампании
        UpdateCampaignBudget::dispatch($campaign);
    }
}
