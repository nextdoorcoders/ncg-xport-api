<?php

namespace App\Services\Marketing;

use App\Exceptions\MessageException;
use App\Models\Account\User as UserModel;
use App\Models\Marketing\Campaign as CampaignModel;
use App\Models\Trigger\Map as MapModel;
use App\Services\Google\CampaignService as GoogleCampaignService;
use Exception;
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
     * @param UserModel $user
     * @return Collection
     */
    public function allCampaigns(UserModel $user)
    {
        $campaigns = $user->campaigns()
            ->with('map')
            ->get();

        $importedCampaigns = CampaignModel::query()
            ->whereIn('foreign_campaign_id', $campaigns->pluck('foreign_campaign_id'))
            ->get();

        $campaigns->each(function (CampaignModel $campaign) use ($importedCampaigns) {
            $count = $importedCampaigns->where('foreign_campaign_id', $campaign->foreign_campaign_id)->count();

            $campaign->setAttribute('campaign_count', $count);
        });

        return $campaigns;
    }

    /**
     * @param MapModel  $map
     * @param UserModel $user
     * @return Collection
     */
    public function allMapCampaigns(MapModel $map, UserModel $user)
    {
        $campaigns = $map->campaigns()
            ->with('map')
            ->get();

        $importedCampaigns = CampaignModel::query()
            ->whereIn('foreign_campaign_id', $campaigns->pluck('foreign_campaign_id'))
            ->get();

        $campaigns->each(function (CampaignModel $campaign) use ($importedCampaigns) {
            $count = $importedCampaigns->where('foreign_campaign_id', $campaign->foreign_campaign_id)->count();

            $campaign->setAttribute('campaign_count', $count);
        });

        return $campaigns;
    }

    /**
     * @param MapModel  $map
     * @param UserModel $user
     * @param array     $data
     * @return CampaignModel|null
     * @throws MessageException
     */
    public function createMapCampaign(MapModel $map, UserModel $user, array $data)
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
            (is_null($project->date_start_at) || now()->greaterThanOrEqualTo($project->date_start_at->startOfDay())) &&
            (is_null($project->date_end_at) || now()->lessThanOrEqualTo($project->date_end_at->endOfDay()))
        ) {
            $campaign->is_enabled = true;
        } else {
            $campaign->is_enabled = false;
        }

        if ($campaign->isDirty('is_enabled')) {
            $campaign->save();
        }

        $this->googleCampaignService->updateCampaignStatus($campaign);
    }
}
