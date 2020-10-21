<?php

namespace App\Http\Resources\Geo;

use App\Http\Resources\Traits\ResourceTrait;
use App\Http\Resources\Trigger\Vendor;
use App\Http\Resources\Trigger\VendorType;
use App\Models\Trigger\VendorLocation as VendorLocationModel;
use App\Models\Trigger\VendorType as VendorTypeModel;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorList extends JsonResource
{
    use ResourceTrait;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->resource;

        if ($resource instanceof VendorTypeModel) {
            /** @var VendorTypeModel $resource */
            $response = [
                'id'        => $resource->id,
                'vendor_id' => $resource->vendor_id,
                'type'      => 'global',
                'name'      => $resource->name,
                'desc'      => $resource->desc,
                'vendor'    => new Vendor($resource->vendor),
            ];
        } elseif ($resource instanceof VendorLocationModel) {
            /** @var VendorLocationModel $resource */
            $response = [
                'id'             => $resource->id,
                'vendor_type_id' => $resource->vendor_type_id,
                'type'           => 'local',
                'name'           => $resource->vendorType->name,
                'desc'           => $resource->vendorType->desc,
                'location'       => new Location($resource->location),
                'vendorType'     => new VendorType($resource->vendorType),
            ];
        } else {
            $response = $resource;
        }

        return $response;
    }
}
