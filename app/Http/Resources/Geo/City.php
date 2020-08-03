<?php

namespace App\Http\Resources\Geo;

use App\Http\Resources\Traits\ResourceTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class City extends JsonResource
{
    use ResourceTrait;

    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\Geo\City $resource */
        $resource = $this->resource;

        $response = [
            'id'   => $resource->id,
            'name' => $resource->name,
        ];

        if ($resource->relationLoaded('country')) {
            $response = array_merge($response, [
                'country' => new Country($resource->country),
            ]);
        }

        if ($resource->relationLoaded('state')) {
            $response = array_merge($response, [
                'state' => new State($resource->state),
            ]);
        }

        return $response;
    }
}
