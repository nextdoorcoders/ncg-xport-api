<?php

namespace App\Http\Resources\Trigger;

use App\Http\Resources\Account\User;
use App\Http\Resources\Marketing\CampaignCollection;
use App\Http\Resources\Marketing\Project;
use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Trigger\Map as MapModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Map extends JsonResource
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
        /** @var MapModel $resource */
        $resource = $this->resource;

        $response = [
            'id'             => $resource->id,
            'user_id'        => $resource->user_id,
            'project_id'     => $resource->project_id,
            'name'           => $resource->name,
            'desc'           => $resource->desc,
            'is_enabled'     => $resource->is_enabled,
            'refreshed_at'   => $resource->refreshed_at,
            'changed_at'     => $resource->changed_at,
            'shutdown_delay' => $resource->shutdown_delay,
            'shutdown_in'    => $resource->shutdown_in,
            'created_at'     => $resource->created_at,
            'updated_at'     => $resource->updated_at,
        ];

        if ($resource->relationLoaded('user')) {
            $response = array_merge($response, [
                'user' => new User($resource->user),
            ]);
        }

        if ($resource->relationLoaded('project')) {
            $response = array_merge($response, [
                'project' => new Project($resource->project),
            ]);
        }

        if ($resource->relationLoaded('campaigns')) {
            $response = array_merge($response, [
                'campaigns' => new CampaignCollection($resource->campaigns),
            ]);
        }

        if ($resource->relationLoaded('groups')) {
            $response = array_merge($response, [
                'groups' => new GroupCollection($resource->groups),
            ]);
        }

        return $response;
    }
}
