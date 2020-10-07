<?php

namespace App\Services\Marketing;

use App\Exceptions\MessageException;
use App\Models\Account\User as UserModel;
use App\Models\Marketing\Campaign as CampaignModel;
use App\Models\Marketing\Project as ProjectModel;
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
     * @param ProjectModel $project
     * @param UserModel    $user
     * @return Collection
     */
    public function allCampaigns(ProjectModel $project, UserModel $user)
    {
        return $project->campaigns()
            ->get();
    }

    /**
     * @param ProjectModel $project
     * @param UserModel    $user
     * @param array        $data
     * @return CampaignModel|null
     * @throws MessageException
     */
    public function createCampaign(ProjectModel $project, UserModel $user, array $data)
    {
        $campaign = CampaignModel::query()
            ->where('project_id', $project->id)
            ->where('foreign_campaign_id', $data['foreign_campaign_id'])
            ->first();

        if ($campaign) {
            throw new MessageException('Campaign already imported to the project');
        }

        /** @var CampaignModel $campaign */
        $campaign = $project->campaigns()
            ->create($data);

        return $this->readCampaign($project, $campaign, $user);
    }

    /**
     * @param ProjectModel  $project
     * @param CampaignModel $campaign
     * @param UserModel     $user
     * @return CampaignModel|null
     */
    public function readCampaign(ProjectModel $project, CampaignModel $campaign, UserModel $user)
    {
        return $campaign->fresh([
            'project',
        ]);
    }

    /**
     * @param ProjectModel  $project
     * @param CampaignModel $campaign
     * @param UserModel     $user
     * @param array         $data
     * @return CampaignModel|null
     */
    public function updateCampaign(ProjectModel $project, CampaignModel $campaign, UserModel $user, array $data)
    {
        $campaign->fill($data);
        $campaign->save();

        return $this->readCampaign($project, $campaign, $user);
    }

    /**
     * @param ProjectModel  $project
     * @param CampaignModel $campaign
     * @param UserModel     $user
     * @throws Exception
     */
    public function deleteCampaign(ProjectModel $project, CampaignModel $campaign, UserModel $user)
    {
        try {
            $campaign->delete();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param CampaignModel $campaign
     * @throws MessageException
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

        $project->campaigns->each(function (CampaignModel $campaign) use ($status) {
            $this->googleCampaignService->updateCampaignStatus($campaign, $status);
        });
    }
}
