<?php

namespace App\Http\Resources\Geo;

use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Trigger\VendorType as VendorTypeModel;
use App\Models\Trigger\VendorLocation as VendorLocationModel;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorType extends JsonResource
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
                'id'       => $resource->id,
                'type'     => 'global',
                'name'     => $resource->name,
                'desc'     => $resource->desc,
                'location' => null,
            ];
        } elseif ($resource instanceof VendorLocationModel) {
            /** @var VendorLocationModel $resource */
            $response = [
                'id'       => $resource->id,
                'type'     => 'local',
                'name'     => $resource->vendorType->name,
                'desc'     => $resource->vendorType->desc,
                'location' => new Location($resource->location),
            ];
        } else {
            $response = $resource;
        }

        return $response;
    }
}
