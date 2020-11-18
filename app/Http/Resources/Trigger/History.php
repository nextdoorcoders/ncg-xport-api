<?php

namespace App\Http\Resources\Trigger;

use App\Models\Traits\UuidTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class History extends JsonResource
{
    use UuidTrait;

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
