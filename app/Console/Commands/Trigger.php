<?php

namespace App\Console\Commands;

use App\Models\Marketing\Campaign as CampaignModel;
use App\Models\Marketing\Condition as ConditionModel;
use App\Models\Marketing\Group as GroupModel;
use App\Models\Marketing\Project as ProjectModel;
use App\Services\Google\AdWords\CampaignService;
use App\Services\Marketing\Vendor\BaseVendor;
use Google\AdsApi\AdWords\v201809\cm\CampaignStatus;
use Illuminate\Console\Command;

class Trigger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trigger:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $campaignService;

    /**
     * Campaign constructor.
     *
     * @param CampaignService $campaignService
     */
    public function __construct(CampaignService $campaignService)
    {
        parent::__construct();

        $this->campaignService = $campaignService;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        /*
         * У нас имеется иерархия из 3х сущностей Project <- Group <- Condition
         * Проверяем значение каждого состояния, на основании этого проводим
         * логические операции для группы и для проекта в целом. Именно в таком порядке,
         * из конца в начало
         */

        /*
         * Conditions
         */
        $conditions = ConditionModel::query()
            ->with([
                'vendorLocation' => function ($query) {
                    $query->with('vendor');
                },
            ])
            ->get();

        $conditions->each(function (ConditionModel $condition) {
            $vendorLocation = $condition->vendorLocation;
            $vendor = $vendorLocation->vendor;

            /** @var BaseVendor $triggerClass */
            $triggerClass = app($vendor->trigger_class);
            $isTriggerEnabled = $triggerClass->run($condition);

            $condition->is_trigger_enabled = $isTriggerEnabled;
            $condition->trigger_refreshed_at = now();

            if ($condition->isDirty('is_trigger_enabled') || $condition->trigger_changed_at == null) {
                $condition->trigger_changed_at = now();
            }

            $condition->save();
        });

        /*
         * Groups
         */
        $groups = GroupModel::query()
            ->with('conditions')
            ->get();

        $groups->each(function (GroupModel $group) {
            $totalCountOfConditions = $group->conditions
                ->count();

            $countOfEnabledConditions = $group->conditions
                ->where('is_trigger_enabled', true)
                ->count();

            if ($totalCountOfConditions == 0 || $countOfEnabledConditions > 0) {
                $group->is_trigger_enabled = true;
            } else {
                $group->is_trigger_enabled = false;
            }

            $group->trigger_refreshed_at = now();

            if ($group->isDirty('is_trigger_enabled') || $group->trigger_changed_at == null) {
                $group->trigger_changed_at = now();
            }

            $group->save();
        });

        /*
         * Projects
         */
        $projects = ProjectModel::query()
            ->with('groups')
            ->get();

        $projects->each(function (ProjectModel $project) {
            $totalCountOfGroups = $project->groups
                ->count();

            $countOfEnabledGroups = $project->groups
                ->where('is_trigger_enabled', true)
                ->count();

            if ($totalCountOfGroups > 0 && $totalCountOfGroups === $countOfEnabledGroups) {
                $project->is_trigger_enabled = true;
            } else {
                $project->is_trigger_enabled = false;
            }

            $project->trigger_refreshed_at = now();

            if ($project->isDirty('is_trigger_enabled') || $project->trigger_changed_at == null) {
                $project->trigger_changed_at = now();
            }

            $project->save();
        });

        /*
         * Campaigns
         */
        $campaigns = CampaignModel::query()
            ->with([
                'project',
                'account',
            ])
            ->whereHas('project')
            ->get();

        $campaigns->each(function (CampaignModel $campaign) {
            $project = $campaign->project;

            if ($project->is_trigger_enabled) {
                $status = CampaignStatus::ENABLED;
            } else {
                $status = CampaignStatus::PAUSED;
            }

            $this->campaignService->updateCampaignStatus($campaign, $status);
        });
    }
}
