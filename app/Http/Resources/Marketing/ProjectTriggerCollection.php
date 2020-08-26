<?php

namespace App\Http\Resources\Marketing;

use App\Models\Traits\UuidTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectTriggerCollection extends ResourceCollection
{
    use UuidTrait;

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
