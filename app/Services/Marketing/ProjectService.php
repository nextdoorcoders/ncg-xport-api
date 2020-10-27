<?php

namespace App\Services\Marketing;

use App\Models\Account\User as UserModel;
use App\Models\Marketing\Project as ProjectModel;
use App\Models\Trigger\Map as MapModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class ProjectService
{
    /**
     * @param UserModel $user
     * @return Collection
     */
    public function allProjects(UserModel $user)
    {
        return $user->projects()
            ->with('account')
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
            'account',
            'organization',
        ]);
    }

    /**
     * @param ProjectModel $project
     * @param UserModel    $user
     * @param array        $data
     * @return ProjectModel|null
     * @throws Exception
     */
    public function updateProject(ProjectModel $project, UserModel $user, array $data)
    {
        try {
            DB::beginTransaction();

            $project->fill($data);

            $params = $data['parameters'] ?? [];
            $originalParams = $project->getOriginal('parameters');

            if (
                $project->isDirty('account_id') ||
                $params['account_id'] !== $originalParams['account_id'] ||
                $params['developer_token'] !== $originalParams['developer_token']
            ) {
                $maps = $project->maps()
                    ->get();

                $maps->each(function (MapModel $map) {
                    $map->campaigns()->delete();
                });
            }

            $project->save();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }

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
     * @return Collection
     */
    public function readMaps(ProjectModel $project, UserModel $user)
    {
        return $project->maps()
            ->get();
    }
}
