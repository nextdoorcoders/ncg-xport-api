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

class ProjectService
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
    public function allProjects(UserModel $user)
    {
        return $user->projects()
            ->with('socialAccount')
            ->get();
    }

    /**
     * @param UserModel $user
     * @param array     $data
     * @return ProjectModel
     */
    public function createProject(UserModel $user, array $data)
    {
        /** @var ProjectModel $project */
        $project = $user->projects()
            ->create($data);

        return $this->readProject($project, $user);
    }

    /**
     * @param ProjectModel $project
     * @param UserModel    $user
     * @return ProjectModel|null
     */
    public function readProject(ProjectModel $project, UserModel $user)
    {
        return $project->fresh([
            'socialAccount',
            'organization',
            'map',
        ]);
    }

    /**
     * @param ProjectModel $project
     * @param UserModel    $user
     * @param array        $data
     * @return ProjectModel|null
     */
    public function updateProject(ProjectModel $project, UserModel $user, array $data)
    {
        $project->fill($data);
        $project->save();

        return $this->readProject($project, $user);
    }

    /**
     * @param ProjectModel $project
     * @param UserModel    $user
     * @throws Exception
     */
    public function deleteProject(ProjectModel $project, UserModel $user)
    {
        try {
            $project->delete();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @throws MessageException
     */
    public function updateAllStatuses(): void
    {
        $projects = ProjectModel::query()
            ->with([
                'map',
                'campaigns',
            ])
            ->whereHas('map')
            ->where('date_start_at', '<=', now())
            ->where('date_end_at', '>=', now())
            ->get();

        $projects->each(function (ProjectModel $project) {
            $this->updateStatus($project);
        });
    }

    /**
     * @param ProjectModel $project
     * @throws MessageException
     */
    public function updateStatus(ProjectModel $project): void
    {
        $map = $project->map;

        if ($map->is_enabled) {
            $status = CampaignStatus::ENABLED;
        } else {
            $status = CampaignStatus::PAUSED;
        }

        $project->campaigns->each(function (CampaignModel $campaign) use ($status) {
            $this->googleCampaignService->updateCampaignStatus($campaign, $status);
        });
    }
}
