<?php

namespace App\Services\Trigger;

use App\Models\Account\User as UserModel;
use App\Models\Trigger\Group as GroupModel;
use App\Models\Trigger\Map as MapModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class GroupService
{
    /**
     * @param MapModel  $project
     * @param UserModel $user
     * @return Collection
     */
    public function allGroups(MapModel $project, UserModel $user)
    {
        return $project->groups()
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
    }

    /**
     * @param MapModel  $project
     * @param UserModel $user
     * @param array     $data
     * @return Model
     */
    public function createGroup(MapModel $project, UserModel $user, array $data)
    {
        return $project->groups()
            ->create($data);
    }

    /**
     * @param GroupModel $group
     * @param UserModel  $user
     * @return GroupModel
     */
    public function readGroup(GroupModel $group, UserModel $user)
    {
        $group->load([
            'conditions' => function ($query) {
                $query->with('vendor');
            },
        ]);

        return $group;
    }

    /**
     * @param GroupModel $group
     * @param UserModel  $user
     * @param array      $data
     * @return GroupModel|null
     */
    public function updateGroup(GroupModel $group, UserModel $user, array $data)
    {
        $group->fill($data);
        $group->save();

        return $group->fresh();
    }

    /**
     * @param GroupModel $group
     * @param UserModel  $user
     * @throws Exception
     */
    public function deleteGroup(GroupModel $group, UserModel $user)
    {
        try {
            $group->delete();
        } catch (Exception $exception) {
            throw $exception;
        }
    }
}
