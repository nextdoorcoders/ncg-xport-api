<?php

namespace App\Http\Resources\Geo;

use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Trigger\Vendor as VendorModel;
use App\Models\Trigger\VendorLocation as VendorLocationModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Vendor extends JsonResource
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

        if ($resource instanceof VendorModel) {
            /** @var VendorModel $resource */
            $response = [
                'id'       => $resource->id,
                'type'     => VendorModel::LOCATION_GLOBAL,
                'name'     => $resource->name,
                'desc'     => $resource->desc,
                'location' => null,
            ];
        } elseif ($resource instanceof VendorLocationModel) {
            /** @var VendorLocationModel $resource */
            $response = [
                'id'       => $resource->id,
                'type'     => VendorModel::LOCATION_LOCAL,
                'name'     => $resource->vendor->name,
                'desc'     => $resource->vendor->desc,
                'location' => new Location($resource->location),
            ];
        } else {
            $response = $resource;
        }

        return $response;
    }
}
