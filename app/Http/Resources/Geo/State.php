<?php

namespace App\Http\Resources\Geo;

use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Geo\State as StateModel;
use Illuminate\Http\Resources\Json\JsonResource;

class State extends JsonResource
{
    use ResourceTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var StateModel $resource */
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

        if ($resource->relationLoaded('cities')) {
            $response = array_merge($response, [
                'cities' => new CityCollection($resource->cities),
            ]);
        }

        return $response;
    }
}
