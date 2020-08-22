<?php

namespace App\Http\Resources\Account;

use App\Http\Resources\Account\Language;
use App\Http\Resources\Geo\Country;
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

        if ($resource->relationLoaded('country')) {
            $response = array_merge($response, [
                'country' => new Country($resource->country),
            ]);
        }

        if ($resource->relationLoaded('language')) {
            $response = array_merge($response, [
                'language' => new Language($resource->language),
            ]);
        }

        return $response;
    }
}
