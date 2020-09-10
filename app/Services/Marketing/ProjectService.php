<?php

namespace App\Services\Marketing;

use App\Models\Account\User as UserModel;
use App\Models\Marketing\Project as ProjectModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class ProjectService
{
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
        return $project->fresh('socialAccount');
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
}
