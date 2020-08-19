<?php

namespace App\Services\Marketing;

use App\Models\Account\User as UserModel;
use App\Models\Marketing\Project as ProjectModel;
use Exception;

class ProjectService
{
    public function allProjects(UserModel $user)
    {
        return $user->projects()
            ->get();
    }

    public function createProjects(UserModel $user, array $data): ProjectModel
    {
        $project = $user->projects()
            ->create($data);

        return $project;
    }

    public function readProject(ProjectModel $project, UserModel $user)
    {
        return $project;
    }

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

    public function replicateProject(ProjectModel $project, UserModel $user)
    {
        $replicate = $project->replicate();
        $replicate->push();

        return $replicate;
    }
}
