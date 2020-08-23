<?php

namespace App\Services\Marketing;

use App\Models\Account\User as UserModel;
use App\Models\Marketing\Group as GroupModel;
use App\Models\Marketing\Project as ProjectModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class GroupService
{
    /**
     * @param ProjectModel $project
     * @param UserModel    $user
     * @return Collection
     */
    public function allGroups(ProjectModel $project, UserModel $user)
    {
        return $project->groups()
            ->get();
    }

    /**
     * @param ProjectModel $project
     * @param UserModel    $user
     * @param array        $data
     * @return Model
     */
    public function createGroup(ProjectModel $project, UserModel $user, array $data)
    {
        return $project->groups()
            ->create($data);
    }

    /**
     * @param ProjectModel $project
     * @param GroupModel   $group
     * @param UserModel    $user
     * @return GroupModel
     */
    public function readGroup(ProjectModel $project, GroupModel $group, UserModel $user)
    {
        $group->load([
            'conditions' => function ($query) {
                $query->with('vendor');
            },
        ]);

        return $group;
    }

    /**
     * @param ProjectModel $project
     * @param GroupModel   $group
     * @param UserModel    $user
     * @param array        $data
     * @return GroupModel|null
     */
    public function updateGroup(ProjectModel $project, GroupModel $group, UserModel $user, array $data)
    {
        $group->fill($data);
        $group->save();

        return $group->fresh();
    }

    /**
     * @param ProjectModel $project
     * @param GroupModel   $group
     * @param UserModel    $user
     * @throws Exception
     */
    public function deleteGroup(ProjectModel $project, GroupModel $group, UserModel $user)
    {
        try {
            $group->delete();
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
