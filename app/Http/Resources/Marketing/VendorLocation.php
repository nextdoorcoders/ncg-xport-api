<?php

namespace App\Http\Resources\Marketing;

use App\Http\Resources\Geo\City;
use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Marketing\VendorLocation as VendorLocationModel;
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

        if ($resource->relationLoaded('city')) {
            $response = array_merge($response, [
                'city' => new City($resource->city),
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
