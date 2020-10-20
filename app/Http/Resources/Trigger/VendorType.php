<?php

namespace App\Http\Resources\Trigger;

use App\Http\Resources\Geo\LocationCollection;
use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Trigger\VendorType as VendorTypeModel;
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
        /** @var VendorTypeModel $resource */
        $resource = $this->resource;

        $response = [
            'id'                 => $resource->id,
            'vendor_id'          => $resource->vendor_id,
            'name'               => $resource->name,
            'desc'               => $resource->desc,
            'type'               => $resource->type,
            'default_parameters' => $resource->default_parameters,
            'settings'           => $resource->settings,
            'created_at'         => $resource->created_at,
            'updated_at'         => $resource->updated_at,
        ];

        if ($resource->relationLoaded('vendor')) {
            $response = array_merge($response, [
                'vendor' => new Vendor($resource->vendor),
            ]);
        }

        if ($resource->relationLoaded('vendorsLocations')) {
            $response = array_merge($response, [
                'vendorsLocations' => new VendorLocationCollection($resource->vendorsLocations),
            ]);
        }

        if ($resource->relationLoaded('conditions')) {
            $response = array_merge($response, [
                'conditions' => new ConditionCollection($resource->conditions),
            ]);
        }

        if ($resource->relationLoaded('locations')) {
            $response = array_merge($response, [
                'locations' => new LocationCollection($resource->locations),
            ]);
        }

        return $response;
    }
}
