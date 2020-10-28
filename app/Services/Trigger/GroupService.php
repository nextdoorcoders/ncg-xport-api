<?php

namespace App\Services\Trigger;

use App\Exceptions\MessageException;
use App\Models\Account\User as UserModel;
use App\Models\Trigger\Group as GroupModel;
use App\Models\Trigger\Map as MapModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class GroupService
{
    protected MapService $mapService;

    /**
     * GroupService constructor.
     *
     * @param MapService $mapService
     */
    public function __construct(MapService $mapService)
    {
        $this->mapService = $mapService;
    }

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

    /**
     * @throws MessageException
     */
    public function updateAllStatuses(): void
    {
        $groups = GroupModel::query()
            ->with('conditions')
            ->get();

        $groups->each(function (GroupModel $group) {
            $this->updateStatus($group);
        });

        $this->mapService->updateAllStatuses();
    }

    /**
     * Проверка текущего состояние триггера
     *
     * @param GroupModel $group
     * @param bool       $checkParent
     */
    public function updateStatus(GroupModel $group, bool $checkParent = false): void
    {
        $countOfEnabledConditions = $group->conditions
            ->where('is_enabled', true)
            ->count();

        if ($countOfEnabledConditions > 0) {
            $group->is_enabled = true;
        } else {
            $group->is_enabled = false;
        }

        $group->refreshed_at = now();

        $isEnabledSwitch = $group->isDirty('is_enabled');

        if ($isEnabledSwitch || $group->changed_at == null) {
            $group->changed_at = now();
        }

        $group->save([
            'timestamps' => false,
        ]);

        if ($checkParent && $isEnabledSwitch) {
            /*
             * В случае если запуск метода был единичным а не из
             * коллекции - проверяем значение модели уровнем выше
             */

            /** @var MapModel $map */
            $map = $group->map()->first();

            $this->mapService->updateStatus($map, true);
        }
    }
}
