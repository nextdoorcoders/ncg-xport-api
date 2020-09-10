<?php

namespace App\Services\Trigger\Vendor;

use App\Models\Trigger\Condition as ConditionModel;

interface VendorInterface
{
    public function run(ConditionModel $condition);

    public function current($cityId);
}
