<?php

namespace App\Http\Resources\Account;

use App\Http\Resources\Traits\ResourceTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialAccountLink extends JsonResource
{
    use ResourceTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
