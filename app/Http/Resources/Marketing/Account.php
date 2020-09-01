<?php

namespace App\Http\Resources\Marketing;

use App\Http\Resources\Account\SocialAccount;
use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Marketing\Account as AccountModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Account extends JsonResource
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
        /** @var AccountModel $resource */
        $resource = $this->resource;

        $response = [
            'id'                => $resource->id,
            'social_account_id' => $resource->social_account_id,
            'name'              => $resource->name,
            'parameters'        => $resource->parameters,
        ];

        if ($resource->relationLoaded('socialAccount')) {
            $response = array_merge($response, [
                'socialAccount' => new SocialAccount($resource->socialAccount),
            ]);
        }

        return $response;
    }
}
