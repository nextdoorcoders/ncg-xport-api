<?php

namespace App\Services\Marketing;

use App\Models\Account\User as UserModel;
use App\Models\Marketing\Condition as ConditionModel;
use App\Models\Marketing\Group as GroupModel;
use App\Models\Marketing\Project as ProjectModel;
use App\Models\Marketing\VendorLocation as VendorLocationModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use stdClass;

class ProjectService
{
    /**
     * @param UserModel $user
     * @return Collection
     */
    public function allProjects(UserModel $user)
    {
        return $user->projects()
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
        $replicate = $project->replicate();
        $replicate->desc = sprintf('(replicated %s) - %s', now()->format('H:i:s, d.m.Y'), $replicate->desc);
        $replicate->push();

        return $this->readProject($replicate, $user);
    }

    /**
     * @param ProjectModel $project
     * @param UserModel    $user
     * @return Collection
     */
    public function allTriggers(ProjectModel $project, UserModel $user)
    {
        $vendorColumn = new stdClass();
        $vendorColumn->name = 'Vendors in the city';
        $vendorColumn->desc = 'All triggers available for use in the current project are collected here';
        $vendorColumn->vendorsLocation = VendorLocationModel::query()
            ->with([
                'vendor',
            ])
            ->where('city_id', $project->city_id)
            ->whereDoesntHave('groups', function ($query) use ($project) {
                $query->where('project_id', $project->id);
            })
            ->get();

        $groupColumns = $project->groups()
            ->with([
                'conditions' => function ($query) {
                    $query->with([
                        'vendorLocation' => function ($query) {
                            $query->with('vendor');
                        },
                    ]);
                },
            ])
            ->get();

        return $groupColumns->prepend($vendorColumn);
    }

    public function updateTriggers(ProjectModel $project, UserModel $user, array $data)
    {
        return $this->allTriggers($project, $user);
    }
}
