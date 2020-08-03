<?php

namespace App\Http\Resources\Geo;

use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Geo\Country as CountryModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Country extends JsonResource
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
        /** @var CountryModel $resource */
        $resource = $this->resource;

        $response = [
            'id'         => $resource->id,
            'name'       => $resource->name,
            'phone_mask' => $resource->phone_mask,
        ];

        if ($resource->relationLoaded('states')) {
            $response = array_merge($response, [
                'states' => new StateCollection($resource->states),
            ]);
        }

        return $response;
    }
}
