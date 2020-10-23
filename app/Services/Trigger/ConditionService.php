<?php

namespace App\Services\Trigger;

use App\Exceptions\MessageException;
use App\Models\Account\User as UserModel;
use App\Models\Trigger\Condition as ConditionModel;
use App\Models\Trigger\Group as GroupModel;
use App\Models\Trigger\VendorLocation as VendorLocationModel;
use App\Models\Trigger\VendorType as VendorTypeModel;
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
     * @param VendorTypeModel $vendor
     * @param UserModel       $user
     * @return Collection
     */
    public function allByVendor(VendorTypeModel $vendor, UserModel $user)
    {
        return $vendor->conditions()
            ->get();
    }

    /**
     * @param GroupModel $group
     * @param UserModel  $user
     * @param array      $data
     * @return ConditionModel
     * @throws MessageException
     */
    public function createCondition(GroupModel $group, UserModel $user, array $data)
    {
        $vendorId = $data['id'];
        $vendorType = $data['type'];

        /** @var ConditionModel $condition */
        if ($vendorType === 'global') {
            /** @var VendorTypeModel $vendorType */
            $vendorType = VendorTypeModel::query()
                ->where('id', $vendorId)
                ->first();

            $data = [
                'group_id'           => $group->id,
                'vendor_type_id'     => $vendorType->id,
                'vendor_location_id' => null,
                'parameters'         => $vendorType->default_parameters,
            ];
        } elseif ($vendorType === 'local') {
            /** @var VendorLocationModel $vendorLocation */
            $vendorLocation = VendorLocationModel::query()
                ->with('vendorType')
                ->where('id', $vendorId)
                ->first();

            $data = [
                'group_id'           => $group->id,
                'vendor_type_id'     => $vendorLocation->vendor_type_id,
                'vendor_location_id' => $vendorLocation->id,
                'parameters'         => $vendorLocation->vendorType->default_parameters,
            ];
        } else {
            throw new MessageException('Unknown trigger location');
        }

        $condition = app(ConditionModel::class);
        $condition->fill($data);

        $isNeedCheckStatus = false;
        if ($condition->isDirty('parameters')) {
            $isNeedCheckStatus = true;
        }

        $condition->save();

        if ($isNeedCheckStatus == true) {
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
     * @throws MessageException
     */
    public function updateCondition(ConditionModel $condition, UserModel $user, array $data)
    {
        $condition->fill($data);

        $isNeedCheckStatus = false;
        if ($condition->isDirty('parameters')) {
            $isNeedCheckStatus = true;
        }

        $condition->save();

        if ($isNeedCheckStatus == true) {
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
     * @throws MessageException
     */
    public function updateAllStatuses(): void
    {
        $conditions = ConditionModel::query()
            ->with('vendorType.vendor')
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
     * @throws MessageException
     */
    public function updateStatus(ConditionModel $condition, bool $checkParent = false): void
    {
        $vendorType = $condition->vendorType;

        /** @var BaseVendor $triggerClass */
        $triggerClass = app($vendorType->vendor->callback);
        $isEnabled = $triggerClass->checkCondition($condition);

        $condition->is_enabled = $isEnabled;
        $condition->refreshed_at = now();

        $isEnabledSwitched = $condition->isDirty('is_enabled');

        if ($isEnabledSwitched || $condition->changed_at == null) {
            $condition->changed_at = now();
        }

        $condition->save([
            'timestamps' => false,
        ]);

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
