<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\ResourceTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class DataResource extends JsonResource
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
