<?php

namespace App\Services\Trigger;

use App\Models\Trigger\Vendor as VendorModel;
use Illuminate\Database\Eloquent\Collection;

class VendorService
{
    /**
     * @return Collection
     */
    public function allVendors()
    {
        return VendorModel::query()
            ->get();
    }
}
