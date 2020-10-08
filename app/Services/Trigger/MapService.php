<?php

namespace App\Services\Trigger;

use App\Exceptions\MessageException;
use App\Models\Account\User as UserModel;
use App\Models\Trigger\Condition as ConditionModel;
use App\Models\Trigger\Group as GroupModel;
use App\Models\Trigger\Map as MapModel;
use App\Services\Marketing\CampaignService;
use App\Services\Marketing\ProjectService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MapService
{
    protected ProjectService $projectService;

    protected CampaignService $campaignService;

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
     * @return Collection
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
     * @throws Exception
     */
    public function updateMap(MapModel $map, UserModel $user, array $data)
    {
        try {
            DB::beginTransaction();

            $map->fill($data);

            if ($map->isDirty('project_id')) {
                $map->campaigns()->delete();
            }

            $map->save();

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }

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
        $replicateMap->project_id = null;
        $replicateMap->desc = trim(sprintf('(replicated) %s', $replicateMap->desc));
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
     * @throws MessageException
     */
    public function updateAllStatuses(): void
    {
        $maps = MapModel::query()
            ->with('groups')
            ->get();

        $maps->each(function (MapModel $map) {
            $this->updateStatus($map);
        });
    }

    /**
     * Проверка текущего состояние триггера
     *
     * @param MapModel $map
     * @param bool     $checkParent
     * @throws MessageException
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
            $campaigns = $map->campaigns()->get();

            $campaigns->each(function ($campaign) {
                $this->campaignService->updateStatus($campaign);
            });
        }
    }

    /**
     * Получение списка групп и всех вложенных сущностей
     *
     * @param MapModel  $map
     * @param UserModel $user
     * @return Collection
     */
    public function readConditions(MapModel $map, UserModel $user)
    {
        return $map->groups()
            ->with([
                'conditions' => function ($query) {
                    $query->with([
                        'vendor',
                        'vendorLocation' => function ($query) {
                            $query->with('location');
                        },
                    ])
                        ->orderBy('created_at', 'asc');
                },
            ])
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Метод позволяет перемещать состояния между группами. Обновление
     * параметров состояний происходит непосредственно через обновление
     * состояния
     *
     * @param MapModel  $map
     * @param UserModel $user
     * @param array     $data
     * @return Collection
     */
    public function updateConditions(MapModel $map, UserModel $user, array $data)
    {
        $groups = collect($data);

        $groups->each(function ($group) {
            foreach ($group['conditions'] as $card) {
                /** @var ConditionModel $condition */
                if ($group['id'] !== $card['group_id']) {
                    $condition = ConditionModel::query()
                        ->where('id', $card['id'])
                        ->first();

                    $condition->group()->associate($group['id']);
                    $condition->save();
                }
            }
        });

        return $this->readConditions($map, $user);
    }
}
