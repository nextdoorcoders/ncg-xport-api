<?php

namespace App\Http\Resources\Marketing;

use App\Http\Resources\Account\User;
use App\Http\Resources\Traits\ResourceTrait;
use App\Http\Resources\Trigger\Map;
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
            'map_id'          => $resource->map_id,
            'organization_id' => $resource->organization_id,
            'name'            => $resource->name,
            'parameters'      => $resource->parameters,
            'date_start_at'   => Carbon::parse($resource->date_start_at)->toDateString(),
            'date_end_at'     => Carbon::parse($resource->date_end_at)->toDateString(),
        ];

        if ($resource->relationLoaded('account')) {
            $response = array_merge($response, [
                'account' => new Account($resource->account),
            ]);
        }

        if ($resource->relationLoaded('map')) {
            $response = array_merge($response, [
                'map' => new Map($resource->map),
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

        if ($resource->relationLoaded('campaigns')) {
            $response = array_merge($response, [
                'campaigns' => new CampaignCollection($resource->campaigns),
            ]);
        }

        return $response;
    }
}
