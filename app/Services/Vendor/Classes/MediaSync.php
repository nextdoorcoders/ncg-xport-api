<?php

namespace App\Services\Vendor\Classes;

use App\Models\Trigger\Condition as ConditionModel;
use App\Models\Vendor\MediaSync as MediaSyncModel;

class MediaSync extends BaseVendor
{
    /**
     * @param ConditionModel      $condition
     * @param MediaSyncModel|null $current
     * @return bool
     */
    public function check(ConditionModel $condition, MediaSyncModel $current = null): bool
    {
        if (!$current) {
            return false;
        }

        if ($condition->id !== $current->value) {
            return false;
        }

        return true;
    }

    /**
     * @param ConditionModel $condition
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function currentTv(ConditionModel $condition)
    {
        return MediaSyncModel::query()
            ->where('vendor_type_id', $condition->vendor_type_id)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * @param ConditionModel $condition
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function currentRadio(ConditionModel $condition)
    {
        return MediaSyncModel::query()
            ->where('vendor_type_id', $condition->vendor_type_id)
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
