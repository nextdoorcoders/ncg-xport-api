<?php

namespace App\Services\Marketing;

use App\Models\Marketing\Vendor as VendorModel;
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
