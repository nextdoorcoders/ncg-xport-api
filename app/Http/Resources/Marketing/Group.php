<?php

namespace App\Http\Resources\Marketing;

use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Marketing\Group as GroupModel;
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
            'id'   => $resource->id,
            'name' => $resource->name,
            'desc' => $resource->desc,
        ];

        if ($resource->relationLoaded('project')) {
            $response = array_merge($response, [
                'project' => new Project($resource->project),
            ]);
        }

        if ($resource->relationLoaded('conditions')) {
            $response = array_merge($response, [
                'conditions' => new ConditionCollection($resource->conditions),
            ]);
        }

        if ($resource->relationLoaded('vendorsLocation')) {
            $response = array_merge($response, [
                'vendorsLocation' => new VendorCollection($resource->vendorsLocation),
            ]);
        }

        return $response;
    }
}
