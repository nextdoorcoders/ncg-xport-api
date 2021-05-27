<?php

namespace App\Http\Resources\Marketing;

use App\Http\Resources\Account\User;
use App\Http\Resources\Traits\ResourceTrait;
use App\Http\Resources\Trigger\MapCollection;
use App\Models\Marketing\Project as ProjectModel;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Project extends JsonResource
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
        /** @var ProjectModel $resource */
        $resource = $this->resource;

        $response = [
            'id'              => $resource->id,
            'account_id'      => $resource->account_id,
            'organization_id' => $resource->organization_id,
            'name'            => $resource->name,
            'parameters'      => $resource->parameters,
            'is_enabled'      => $resource->is_enabled,
            'platforms'       => $resource->platforms,
            'date_start_at'   => !is_null($resource->date_start_at) ? Carbon::parse($resource->date_start_at)->toDateString() : null,
            'date_end_at'     => !is_null($resource->date_end_at) ? Carbon::parse($resource->date_end_at)->toDateString() : null,
        ];

        if ($resource->relationLoaded('account')) {
            $response = array_merge($response, [
                'account' => new Account($resource->account),
            ]);
        }

        if ($resource->relationLoaded('organization')) {
            $response = array_merge($response, [
                'organization' => new Organization($resource->organization),
            ]);
        }

        if ($resource->relationLoaded('user')) {
            $response = array_merge($response, [
                'user' => new User($resource->user),
            ]);
        }

        if ($resource->relationLoaded('maps')) {
            $response = array_merge($response, [
                'maps' => new MapCollection($resource->maps),
            ]);
        }

        return $response;
    }
}
