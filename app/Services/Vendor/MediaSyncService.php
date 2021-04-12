<?php

namespace App\Services\Vendor;

use App\Models\Trigger\Condition;
use App\Models\Trigger\Vendor;
use App\Models\Trigger\VendorType;
use App\Models\Vendor\MediaSync;

class MediaSyncService
{
    public function updateMediaSync()
    {
//        $vendors = Vendor::query()
//            ->with('vendorsTypes.conditions')
//            ->where('type', 'media_sync')
//            ->get();
//
//        $vendors->each(function (Vendor $vendor) {
//            $vendor->vendorsTypes->each(function (VendorType $vendorType) {
//                if ($vendorType->conditions->isNotEmpty()) {
//                    /** @var Condition $condition */
//                    $condition = $vendorType->conditions->random();
//
//                    MediaSync::query()
//                        ->create([
//                            'vendor_type_id' => $vendorType->id,
//                            'value'          => $condition->id,
//                        ]);
//                }
//            });
//        });
    }
}
