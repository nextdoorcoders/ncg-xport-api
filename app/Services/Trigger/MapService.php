<?php

namespace App\Services\Trigger;

use App\Exceptions\MessageException;
use App\Models\Account\User as UserModel;
use App\Models\Marketing\Campaign as CampaignModel;
use App\Models\Trigger\Condition as ConditionModel;
use App\Models\Trigger\Group as GroupModel;
use App\Models\Trigger\Map as MapModel;
use App\Services\Marketing\CampaignService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MapService
{
    private CampaignService $campaignService;

    private HistoryService $historyService;

    /**
     * MapService constructor.
     *
     * @param CampaignService $campaignService
     */
    public function __construct(CampaignService $campaignService, HistoryService $historyService)
    {
        $this->campaignService = $campaignService;

        $this->historyService = $historyService;
    }

    /**
     * @param UserModel $user
     * @return Collection
     */
    public function allMaps(UserModel $user)
    {
        $templates = MapModel::query()
            ->with('project')
            ->where('user_id', $user->id)
            ->where('project_id', null)
            ->get();

        $maps = MapModel::query()
            ->with('project')
            ->where('user_id', $user->id)
            ->where('project_id', '!=', null)
            ->get();

        return $templates->merge($maps);
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
     * ???????????????? ???????????????? ?????????????????? ????????????????
     *
     * @param MapModel $map
     * @throws Exception
     */
    public function updateStatus(MapModel $map): void
    {
        try {
            DB::beginTransaction();

            $totalCountOfGroups = $map->groups
                ->count();

            $countOfEnabledGroups = $map->groups
                ->where('is_enabled', true)
                ->count();

            if ($totalCountOfGroups > 0 && $totalCountOfGroups === $countOfEnabledGroups) {
                // ???????? ?????????? ???????????????????? ?????????? ?????????? ???????????????????? ???????????????????? ?????????? - ???????????????? ??????????
                $map->is_enabled = true;

                // ?????????????????? ?????????? ?????????? ???????????????? ?????????? ?????????? ?????????????????????????? ??????????????????
                $map->shutdown_in = now()->addSeconds($map->shutdown_delay);
            } elseif (now()->greaterThan($map->shutdown_in)) {
                // ?????????? ???????????? ???????????????? ???? ?????? ?????? ???????? ???? ?????????????? ??????????.
                // ???????? ?????????? ???????????????????? ?????????????? - ?????????????????? ??????????
                $map->is_enabled = false;
            }

            $map->refreshed_at = now();

            $isEnabledSwitched = $map->isDirty('is_enabled');

            if ($isEnabledSwitched || $map->changed_at == null) {
                $map->changed_at = now();

                $this->historyService->createHistory($map);
            }

            $map->save([
                'timestamps' => false,
            ]);

            $campaigns = $map->campaigns()->get();

            $campaigns->each(function (CampaignModel $campaign) {
                $this->campaignService->updateStatus($campaign);
            });

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            throw $exception;
        }
    }

    /**
     * ?????????????????? ???????????? ?????????? ?? ???????? ?????????????????? ??????????????????
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
                        'vendorType' => function ($query) {
                            $query->with('vendor');
                        },
                        'vendorLocation' => function ($query) {
                            $query->with('location');
                        },
                    ])
                        ->orderBy('order_index', 'asc')
                        ->orderBy('created_at', 'asc');
                },
            ])
            ->orderBy('order_index', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * ?????????? ?????????????????? ???????????????????? ?????????????????? ?????????? ????????????????. ????????????????????
     * ???????????????????? ?????????????????? ???????????????????? ?????????????????????????????? ?????????? ????????????????????
     * ??????????????????
     *
     * @param MapModel  $map
     * @param UserModel $user
     * @param array     $data
     * @return Collection
     * @throws MessageException
     */
    public function updateConditions(MapModel $map, UserModel $user, array $data)
    {
        $groups = collect($data);

        $groups->each(function ($group) {
            // TODO: Update order_index for groups

            foreach ($group['conditions'] as $index => $card) {
                /** @var ConditionModel $condition */
                $condition = ConditionModel::query()
                    ->where('id', $card['id'])
                    ->first();

                if ($condition) {
                    if ($group['id'] !== $card['group_id']) {
                        // Move condition to another group
                        $condition->group()->associate($group['id']);
                    }

                    $condition->order_index = $index + 1;
                    $condition->save();
                }
            }
        });

        /** @var ConditionService $conditionService */
        $conditionService = app(ConditionService::class);
        $conditionService->updateAllStatuses();

        return $this->readConditions($map, $user);
    }
}
