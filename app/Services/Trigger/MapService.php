<?php

namespace App\Services\Trigger;

use App\Models\Account\User as UserModel;
use App\Models\Trigger\Condition as ConditionModel;
use App\Models\Trigger\Group as GroupModel;
use App\Models\Trigger\Map as MapModel;
use App\Services\Marketing\ProjectService;
use Exception;
use Illuminate\Database\Eloquent\Collection as CollectionDatabase;

class MapService
{
    protected ProjectService $projectService;

    /**
     * MapService constructor.
     *
     * @param ProjectService $projectService
     */
    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * @param UserModel $user
     * @return CollectionDatabase
     */
    public function allMaps(UserModel $user)
    {
        return MapModel::query()
            ->where('user_id', $user->id)
            ->get();
    }

    /**
     * @param UserModel $user
     * @param array     $data
     * @return MapModel|null
     */
    public function createMap(UserModel $user, array $data)
    {
        /** @var MapModel $map */
        $map = $user->maps()
            ->create($data);

        return $this->readMap($map, $user);
    }

    /**
     * @param MapModel  $map
     * @param UserModel $user
     * @return MapModel|null
     */
    public function readMap(MapModel $map, UserModel $user)
    {
        return $map->fresh();
    }

    /**
     * @param MapModel  $map
     * @param UserModel $user
     * @param array     $data
     * @return MapModel|null
     */
    public function updateMap(MapModel $map, UserModel $user, array $data)
    {
        $map->fill($data);
        $map->save();

        return $this->readMap($map, $user);
    }

    /**
     * @param MapModel  $map
     * @param UserModel $user
     * @throws Exception
     */
    public function deleteMap(MapModel $map, UserModel $user)
    {
        try {
            $map->delete();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param MapModel  $map
     * @param UserModel $user
     * @return MapModel|null
     */
    public function replicateMap(MapModel $map, UserModel $user)
    {
        $replicateMap = $map->replicate();
        $replicateMap->desc = sprintf('(replicated %s) - %s', now()->format('H:i:s, d.m.Y'), $replicateMap->desc);
        $replicateMap->push();

        $groups = $map->groups()->get();

        $groups->each(function (GroupModel $group) use ($replicateMap) {
            $replicateGroup = $group->replicate();
            $replicateGroup->map()->associate($replicateMap);
            $replicateGroup->push();

            $conditions = $group->conditions()->get();

            $conditions->each(function (ConditionModel $condition) use ($replicateGroup) {
                $replicateCondition = $condition->replicate();
                $replicateCondition->group()->associate($replicateGroup);
                $replicateCondition->push();
            });
        });

        return $this->readMap($replicateMap, $user);
    }

    /**
     * @throws \App\Exceptions\MessageException
     */
    public function updateAllStatuses(): void
    {
        $maps = MapModel::query()
            ->with('groups')
            ->get();

        $maps->each(function (MapModel $map) {
            $this->updateStatus($map);
        });

        $this->projectService->updateAllStatuses();
    }

    /**
     * Проверка текущего состояние триггера
     *
     * @param MapModel $map
     * @param bool     $checkParent
     * @throws \App\Exceptions\MessageException
     */
    public function updateStatus(MapModel $map, bool $checkParent = false): void
    {
        $totalCountOfGroups = $map->groups
            ->count();

        $countOfEnabledGroups = $map->groups
            ->where('is_enabled', true)
            ->count();

        if ($totalCountOfGroups > 0 && $totalCountOfGroups === $countOfEnabledGroups) {
            $map->is_enabled = true;
        } else {
            $map->is_enabled = false;
        }

        $map->refreshed_at = now();

        $isEnabledSwitched = $map->isDirty('is_enabled');

        if ($isEnabledSwitched || $map->changed_at == null) {
            $map->changed_at = now();
        }

        $map->save();

        if ($checkParent && $isEnabledSwitched) {
            $projects = $map->projects()->get();

            $projects->each(function ($project) {
                $this->projectService->updateStatus($project);
            });
        }
    }

//    /**
//     * @param MapModel $map
//     * @param UserModel    $user
//     * @return Collection
//     */
//    public function allTriggers(MapModel $map, UserModel $user)
//    {
//        $vendorColumn = VendorLocationModel::query()
//            ->with([
//                'vendor',
//            ])
//            ->where('city_id', $map->city_id)
//            ->whereDoesntHave('groups', function ($query) use ($map) {
//                $query->where('map_id', $map->id);
//            })
//            ->orderBy('id', 'asc')
//            ->get();
//
//        $groupColumns = $map->groups()
//            ->with([
//                'conditions' => function ($query) {
//                    $query->with([
//                        'vendorLocation' => function ($query) {
//                            $query->with('vendor');
//                        },
//                    ])
//                        ->orderBy('created_at', 'asc');
//                },
//            ])
//            ->orderBy('created_at', 'asc')
//            ->get();
//
//        $groupColumns->each(function (GroupModel $group) {
//            $group->conditions->each(function (ConditionModel $condition) {
//                /** @var BaseVendor $triggerClass */
//                $triggerClass = app($condition->vendorLocation->vendor->trigger_class);
//
//                $condition->current_value = $triggerClass->current($condition->vendorLocation->city_id);
//            });
//        });
//
//        $response = collect();
//        $response->put('vendors', $vendorColumn);
//        $response->put('groups', $groupColumns);
//
//        return $response;
//    }
//
//    /**
//     * @param MapModel $map
//     * @param UserModel    $user
//     * @param array        $data
//     * @return CollectionDatabase
//     */
//    public function updateTriggers(MapModel $map, UserModel $user, array $data)
//    {
//        $groupColumns = collect($data);
//
//        $groupColumns->each(function ($group) {
//            foreach ($group['conditions'] as $card) {
//                /** @var ConditionModel $condition */
//                if ($card['type'] === 'vendorLocation') {
//                    $condition = app(ConditionModel::class);
//                    $condition->group()->associate($group['id']);
//                    $condition->vendorLocation()->associate($card['id']);
//                    $condition->parameters = $condition->vendorLocation->vendor->default_parameters;
//                    $condition->save();
//                } else {
//                    if ($group['id'] !== $card['group_id']) {
//                        $condition = ConditionModel::query()
//                            ->where('id', $card['id'])
//                            ->first();
//
//                        $condition->group()->associate($group['id']);
//                        $condition->save();
//                    }
//                }
//            }
//        });
//
////        $map->refreshStatus();
//
//        return $this->allTriggers($map, $user);
//    }
}
