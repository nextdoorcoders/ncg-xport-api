<?php

namespace App\Services\Marketing;

use App\Exceptions\MessageException;
use App\Models\Account\User as UserModel;
use App\Models\Marketing\Campaign as CampaignModel;
use App\Models\Trigger\Map as MapModel;
use App\Services\Google\CampaignService as GoogleCampaignService;
use Exception;
use Google\AdsApi\AdWords\v201809\cm\CampaignStatus;
use Illuminate\Database\Eloquent\Collection;

class CampaignService
{
    protected GoogleCampaignService $googleCampaignService;

    /**
     * ProjectService constructor.
     *
     * @param GoogleCampaignService $googleCampaignService
     */
    public function __construct(GoogleCampaignService $googleCampaignService)
    {
        $this->googleCampaignService = $googleCampaignService;
    }

    /**
     * @param MapModel  $map
     * @param UserModel $user
     * @return Collection
     */
    public function allCampaigns(MapModel $map, UserModel $user)
    {
        return $map->campaigns()
            ->get();
    }

    /**
     * @param MapModel  $map
     * @param UserModel $user
     * @param array     $data
     * @return CampaignModel|null
     * @throws MessageException
     */
    public function createCampaign(MapModel $map, UserModel $user, array $data)
    {
        $campaign = CampaignModel::query()
            ->where('map_id', $map->id)
            ->where('foreign_campaign_id', $data['foreign_campaign_id'])
            ->first();

        if ($campaign) {
            throw new MessageException('Campaign already imported to the project');
        }

        /** @var CampaignModel $campaign */
        $campaign = $map->campaigns()
            ->create($data);

        return $this->readCampaign($campaign, $user);
    }

    /**
     * @param CampaignModel $campaign
     * @param UserModel     $user
     * @return CampaignModel|null
     */
    public function readCampaign(CampaignModel $campaign, UserModel $user)
    {
        return $campaign->fresh([
            'map',
        ]);
    }

    /**
     * @param CampaignModel $campaign
     * @param UserModel     $user
     * @param array         $data
     * @return CampaignModel|null
     */
    public function updateCampaign(CampaignModel $campaign, UserModel $user, array $data)
    {
        $campaign->fill($data);
        $campaign->save();

        return $this->readCampaign($campaign, $user);
    }

    /**
     * @param CampaignModel $campaign
     * @param UserModel     $user
     * @throws Exception
     */
    public function deleteCampaign(CampaignModel $campaign, UserModel $user)
    {
        try {
            $campaign->delete();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param CampaignModel $campaign
     */
    public function updateStatus(CampaignModel $campaign): void
    {
        $map = $campaign->map;

        $project = $map->project;

        if (
            $map->is_enabled &&
            $project->is_enabled &&
            now()->greaterThanOrEqualTo($project->date_start_at) &&
            now()->lessThanOrEqualTo($project->date_end_at)
        ) {
            $status = CampaignStatus::ENABLED;
        } else {
            $status = CampaignStatus::PAUSED;
        }

        $this->googleCampaignService->updateCampaignStatus($campaign, $status);
    }
}
