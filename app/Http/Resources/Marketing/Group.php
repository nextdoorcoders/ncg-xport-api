<?php

namespace App\Http\Resources\Marketing;

use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Marketing\Group as GroupModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Group extends JsonResource
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
        /** @var GroupModel $response */
        $response = $this->resource;

        if ($response->relationLoaded('conditions')) {
            $response = array_merge($response, [
                'conditions' => new ConditionCollection($response->conditions),
            ]);
        }

        if ($response->relationLoaded('vendors')) {
            $response = array_merge($response, [
                'vendors' => new VendorCollection($response->vendors),
            ]);
        }

        return $response;
    }
}
