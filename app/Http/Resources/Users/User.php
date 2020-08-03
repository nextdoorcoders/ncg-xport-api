<?php

namespace App\Http\Resources\Users;

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
            'email'      => $resource->email,
            'is_online'  => $resource->is_online,
            'last_login' => $resource->last_login_at,
            'last_seen'  => $resource->last_seen_at,
        ];

        return $response;
    }
}
