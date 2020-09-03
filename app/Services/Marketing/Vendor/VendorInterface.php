<?php

namespace App\Services\Marketing\Vendor;

use App\Models\Marketing\Condition as ConditionModel;

interface VendorInterface
{
    public function run(ConditionModel $condition);

    public function current($cityId);
}
