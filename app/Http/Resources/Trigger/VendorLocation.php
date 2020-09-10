<?php

namespace App\Http\Resources\Trigger;

use App\Http\Resources\Geo\Location;
use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Trigger\VendorLocation as VendorLocationModel;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorLocation extends JsonResource
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
        /** @var VendorLocationModel $resource */
        $resource = $this->resource;

        $response = [
            'id' => $resource->id,
        ];

        if ($resource->relationLoaded('location')) {
            $response = array_merge($response, [
                'location' => new Location($resource->location),
            ]);
        }

        if ($resource->relationLoaded('vendor')) {
            $response = array_merge($response, [
                'vendor' => new Vendor($resource->vendor),
            ]);
        }

        return $response;
    }
}
