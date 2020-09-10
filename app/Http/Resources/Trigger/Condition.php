<?php

namespace App\Http\Resources\Trigger;

use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Trigger\Condition as ConditionModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Condition extends JsonResource
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
        /** @var ConditionModel $resource */
        $resource = $this->resource;

        $response = [
            'id'         => $resource->id,
            'parameters' => $resource->parameters,
        ];

        if ($resource->relationLoaded('group')) {
            $response = array_merge($response, [
                'group' => new Group($resource->group),
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
