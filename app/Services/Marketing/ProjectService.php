<?php

namespace App\Services\Marketing;

use App\Models\Account\User as UserModel;
use App\Models\Marketing\Condition as ConditionModel;
use App\Models\Marketing\Group as GroupModel;
use App\Models\Marketing\Project as ProjectModel;
use App\Models\Marketing\VendorLocation as VendorLocationModel;
use App\Services\Marketing\Vendor\BaseVendor;
use Exception;
use Illuminate\Database\Eloquent\Collection as CollectionDatabase;
use Illuminate\Support\Collection;

class ProjectService
{
    /**
     * @param UserModel $user
     * @return CollectionDatabase
     */
    public function allProjects(UserModel $user)
    {
        return ProjectModel::query()
            ->where('owner_user_id', $user->id)
            ->orWhere('client_user_id', $user->id)
            ->get();
    }

    /**
     * @param UserModel $user
     * @param array     $data
     * @return ProjectModel|null
     */
    public function createProject(UserModel $user, array $data)
    {
        /** @var ProjectModel $project */
        $project = $user->ownerProjects()
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
        return $project->fresh();
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
     * @param ProjectModel $project
     * @param UserModel    $user
     * @return ProjectModel|null
     */
    public function replicateProject(ProjectModel $project, UserModel $user)
    {
        $replicateProject = $project->replicate();
        $replicateProject->desc = sprintf('(replicated %s) - %s', now()->format('H:i:s, d.m.Y'), $replicateProject->desc);
        $replicateProject->push();

        $groups = $project->groups()->get();

        $groups->each(function (GroupModel $group) use ($replicateProject) {
            $replicateGroup = $group->replicate();
            $replicateGroup->project()->associate($replicateProject);
            $replicateGroup->push();

            $conditions = $group->conditions()->get();

            $conditions->each(function (ConditionModel $condition) use ($replicateGroup) {
                $replicateCondition = $condition->replicate();
                $replicateCondition->group()->associate($replicateGroup);
                $replicateCondition->push();
            });
        });

        return $this->readProject($replicateProject, $user);
    }

    /**
     * @param ProjectModel $project
     * @param UserModel    $user
     * @return CollectionDatabase
     */
    public function allCampaigns(ProjectModel $project, UserModel $user)
    {
        return $project->campaigns()
            ->with('account')
            ->get();
    }

    /**
     * @param ProjectModel $project
     * @param UserModel    $user
     * @return Collection
     */
    public function allTriggers(ProjectModel $project, UserModel $user)
    {
        $vendorColumn = VendorLocationModel::query()
            ->with([
                'vendor',
            ])
            ->where('city_id', $project->city_id)
            ->whereDoesntHave('groups', function ($query) use ($project) {
                $query->where('project_id', $project->id);
            })
            ->orderBy('id', 'asc')
            ->get();

        $groupColumns = $project->groups()
            ->with([
                'conditions' => function ($query) {
                    $query->with([
                        'vendorLocation' => function ($query) {
                            $query->with('vendor');
                        },
                    ])
                        ->orderBy('created_at', 'asc');
                },
            ])
            ->orderBy('created_at', 'asc')
            ->get();

        $groupColumns->each(function (GroupModel $group) {
            $group->conditions->each(function (ConditionModel $condition) {
                /** @var BaseVendor $triggerClass */
                $triggerClass = app($condition->vendorLocation->vendor->trigger_class);

                $condition->current_value = $triggerClass->current($condition->vendorLocation->city_id);
            });
        });

        $response = collect();
        $response->put('vendors', $vendorColumn);
        $response->put('groups', $groupColumns);

        return $response;
    }

    /**
     * @param ProjectModel $project
     * @param UserModel    $user
     * @param array        $data
     * @return CollectionDatabase
     */
    public function updateTriggers(ProjectModel $project, UserModel $user, array $data)
    {
        $groupColumns = collect($data);

        $groupColumns->each(function ($group) {
            foreach ($group['conditions'] as $card) {
                /** @var ConditionModel $condition */
                if ($card['type'] === 'vendorLocation') {
                    $condition = app(ConditionModel::class);
                    $condition->group()->associate($group['id']);
                    $condition->vendorLocation()->associate($card['id']);
                    $condition->parameters = $condition->vendorLocation->vendor->default_parameters;
                    $condition->save();
                } else {
                    if ($group['id'] !== $card['group_id']) {
                        $condition = ConditionModel::query()
                            ->where('id', $card['id'])
                            ->first();

                        $condition->group()->associate($group['id']);
                        $condition->save();
                    }
                }
            }
        });

//        $project->refreshStatus();

        return $this->allTriggers($project, $user);
    }
}
