<?php

namespace App\Http\Resources\Marketing;

use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Marketing\Condition as ConditionModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Condition extends JsonResource
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
        /** @var ConditionModel $response */
        $response = $this->resource;

        if ($response->relationLoaded('group')) {
            $response = array_merge($response, [
                'group' => new Group($response->group),
            ]);
        }

        if ($response->relationLoaded('vendor')) {
            $response = array_merge($response, [
                'vendor' => new Vendor($response->vendor),
            ]);
        }

        return $response;
    }
}
