<?php

namespace App\Http\Resources\Marketing;

use App\Http\Resources\Account\SocialAccount;
use App\Http\Resources\Traits\ResourceTrait;
use App\Http\Resources\Trigger\Map;
use App\Models\Marketing\Project as ProjectModel;
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
            'id'                => $resource->id,
            'social_account_id' => $resource->social_account_id,
            'name'              => $resource->name,
            'parameters'        => $resource->parameters,
        ];

        if ($resource->relationLoaded('map')) {
            $response = array_merge($response, [
                'map' => new Map($resource->map),
            ]);
        }

        if ($resource->relationLoaded('socialAccount')) {
            $response = array_merge($response, [
                'socialAccount' => new SocialAccount($resource->socialAccount),
            ]);
        }

        if ($resource->relationLoaded('organization')) {
            $response = array_merge($response, [
                'organization' => new Organization($resource->organization),
            ]);
        }

        if ($resource->relationLoaded('campaigns')) {
            $response = array_merge($response, [
                'campaigns' => new CampaignCollection($resource->campaigns),
            ]);
        }

        if ($resource->relationLoaded('socialAccount')) {
            $response = array_merge($response, [
                'socialAccount' => new SocialAccount($resource->socialAccount),
            ]);
        }

        return $response;
    }
}
