<?php

namespace App\Http\Resources\Trigger;

use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Trigger\Group as GroupModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Group extends JsonResource
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
        /** @var GroupModel $resource */
        $resource = $this->resource;

        $response = [
            'id'           => $resource->id,
            'name'         => $resource->name,
            'desc'         => $resource->desc,
            'order_index'  => $resource->order_index,
            'is_enabled'   => $resource->is_enabled,
            'refreshed_at' => $resource->refreshed_at,
            'changed_at'   => $resource->changed_at,
            'created_at'   => $resource->created_at,
            'updated_at'   => $resource->updated_at,
        ];

        if ($resource->relationLoaded('map')) {
            $response = array_merge($response, [
                'map' => new Map($resource->map),
            ]);
        }

        if ($resource->relationLoaded('conditions')) {
            $response = array_merge($response, [
                'conditions' => new ConditionCollection($resource->conditions),
            ]);
        }

        return $response;
    }
}
