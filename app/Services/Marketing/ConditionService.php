<?php

namespace App\Services;

use App\Models\Account\User as UserModel;
use App\Models\Marketing\Condition as ConditionModel;
use App\Models\Marketing\Group as GroupModel;
use App\Models\Marketing\Vendor as VendorModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class ConditionService
{
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
     * @param GroupModel  $group
     * @param VendorModel $vendor
     * @param UserModel   $user
     * @param array       $data
     * @return ConditionModel
     */
    public function createCondition(GroupModel $group, VendorModel $vendor, UserModel $user, array $data)
    {
        /** @var ConditionModel $condition */
        $condition = app(ConditionModel::class);
        $condition->fill($data);
        $condition->group()->associate($group);
        $condition->vendor()->associate($vendor);
        $condition->save();

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
}
