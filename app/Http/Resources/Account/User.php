<?php

namespace App\Http\Resources\Account;

use App\Http\Resources\Geo\Location;
use App\Http\Resources\Marketing\AccountCollection;
use App\Http\Resources\Marketing\OrganizationCollection;
use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Account\User as UserModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
        /** @var UserModel $resource */
        $resource = $this->resource;

        $response = [
            'id'         => $resource->id,
            'name'       => $resource->name,
            'email'      => $resource->email,
            'is_online'  => $resource->is_online,
            'last_login' => $resource->last_login_at,
            'last_seen'  => $resource->last_seen_at,
        ];

        if ($resource->relationLoaded('location')) {
            $response = array_merge($response, [
                'location' => new Location($resource->location),
            ]);
        }

        if ($resource->relationLoaded('language')) {
            $response = array_merge($response, [
                'language' => new Language($resource->language),
            ]);
        }

        if ($resource->relationLoaded('accounts')) {
            $response = array_merge($response, [
                'accounts' => new AccountCollection($resource->accounts),
            ]);
        }

        if ($resource->relationLoaded('contacts')) {
            $response = array_merge($response, [
                'contacts' => new ContactCollection($resource->contacts),
            ]);
        }

        if ($resource->relationLoaded('organizations')) {
            $response = array_merge($response, [
                'organizations' => new OrganizationCollection($resource->organizations),
            ]);
        }

        return $response;
    }
}
