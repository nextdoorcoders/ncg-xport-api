<?php

namespace App\Services\Trigger;

use App\Models\Account\User as UserModel;
use App\Models\Trigger\Condition as ConditionModel;
use App\Models\Trigger\Group as GroupModel;
use App\Models\Trigger\Vendor as VendorModel;
use App\Models\Trigger\VendorLocation as VendorLocationModel;
use App\Services\Vendor\Classes\BaseVendor;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class ConditionService
{
    protected GroupService $groupService;

    /**
     * ConditionService constructor.
     *
     * @param GroupService $groupService
     */
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * @param GroupModel $group
     * @param UserModel  $user
     * @return Collection
     */
    public function allByGroup(GroupModel $group, UserModel $user)
    {
        return $group->conditions()
            ->get();
    }

    /**
     * @param VendorModel $vendor
     * @param UserModel   $user
     * @return Collection
     */
    public function allByVendor(VendorModel $vendor, UserModel $user)
    {
        return $vendor->conditions()
            ->get();
    }

    /**
     * @param GroupModel          $group
     * @param VendorLocationModel $vendorLocation
     * @param UserModel           $user
     * @param array               $data
     * @return ConditionModel
     */
    public function createCondition(GroupModel $group, VendorLocationModel $vendorLocation, UserModel $user, array $data)
    {
        /** @var ConditionModel $condition */
        $condition = app(ConditionModel::class);
        $condition->fill($data);
        $condition->group()->associate($group);
        $condition->vendorLocation()->associate($vendorLocation);
        $condition->save();

        if ($condition->isDirty('parameters')) {
            $this->updateStatus($condition, true);
        }

        return $this->readCondition($condition, $user);
    }

    /**
     * @param ConditionModel $condition
     * @param UserModel      $user
     * @return ConditionModel|null
     */
    public function readCondition(ConditionModel $condition, UserModel $user)
    {
        return $condition->fresh();
    }

    /**
     * @param ConditionModel $condition
     * @param UserModel      $user
     * @param array          $data
     * @return ConditionModel|null
     */
    public function updateCondition(ConditionModel $condition, UserModel $user, array $data)
    {
        $condition->fill($data);
        $condition->save();

        if ($condition->isDirty('parameters')) {
            $this->updateStatus($condition, true);
        }

        return $this->readCondition($condition, $user);
    }

    /**
     * @param ConditionModel $condition
     * @param UserModel      $user
     * @throws Exception
     */
    public function deleteCondition(ConditionModel $condition, UserModel $user)
    {
        try {
            $condition->delete();
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @throws \App\Exceptions\MessageException
     */
    public function updateAllStatuses(): void
    {
        $conditions = ConditionModel::query()
            ->with('vendor')
            ->get();

        $conditions->each(function (ConditionModel $condition) {
            $this->updateStatus($condition);
        });

        $this->groupService->updateAllStatuses();
    }

    /**
     * Проверка текущего состояния тиггера
     *
     * @param ConditionModel $condition
     * @param bool           $checkParent
     * @throws \App\Exceptions\MessageException
     */
    public function updateStatus(ConditionModel $condition, bool $checkParent = false): void
    {
        $vendor = $condition->vendor;

        /** @var BaseVendor $triggerClass */
        $triggerClass = app($vendor->trigger_class);
        $isEnabled = $triggerClass->run($condition);

        $condition->is_enabled = $isEnabled;
        $condition->refreshed_at = now();

        $isEnabledSwitched = $condition->isDirty('is_enabled');

        if ($isEnabledSwitched || $condition->changed_at == null) {
            $condition->changed_at = now();
        }

        $condition->save();

        if ($checkParent && $isEnabledSwitched) {
            /*
             * В случае если запуск метода был единичным а не из
             * коллекции - проверяем значение модели уровнем выше
             */

            /** @var GroupModel $group */
            $group = $condition->group()->first();

            $this->groupService->updateStatus($group, true);
        }
    }
}
