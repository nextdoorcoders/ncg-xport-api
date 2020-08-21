<?php

namespace App\Services\Marketing;

use App\Models\Account\User as UserModel;
use App\Models\Marketing\Project as ProjectModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

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
     * @return Model
     */
    public function createProject(UserModel $user, array $data)
    {
        return $user->projects()
            ->create($data);
    }

    /**
     * @param ProjectModel $project
     * @param UserModel    $user
     * @return ProjectModel
     */
    public function readProject(ProjectModel $project, UserModel $user)
    {
        return $project;
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

        return $project->fresh();
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
     * @return ProjectModel
     */
    public function replicateProject(ProjectModel $project, UserModel $user)
    {
        $replicate = $project->replicate();
        $replicate->desc = sprintf('(replicated %s) - %s', now()->format('H:i:s, d.m.Y'), $replicate->desc);
        $replicate->push();

        return $replicate;
    }
}
