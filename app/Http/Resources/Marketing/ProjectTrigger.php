<?php

namespace App\Http\Resources\Marketing;

use App\Models\Marketing\Condition as ConditionModel;
use App\Models\Marketing\Group as GroupModel;
use App\Models\Marketing\VendorLocation as VendorLocationModel;
use App\Models\Traits\UuidTrait;
use Illuminate\Http\Resources\Json\JsonResource;
use stdClass;

class ProjectTrigger extends JsonResource
{
    use UuidTrait;

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = $this->resource;

        if ($resource instanceof stdClass) {
            /** @var stdClass $resource */
            return [
                'name'  => $resource->name,
                'desc'  => $resource->desc,
                'cards' => $resource->vendorsLocation->map(function (VendorLocationModel $item) {
                    return [
                        'id'   => $item->id,
                        'name' => $item->vendor->name,
                        'desc' => $item->vendor->desc,
                        'type' => 'vendorLocation',
                    ];
                }),
            ];
        }

        /** @var GroupModel $resource */
        return [
            'name'  => $resource->name,
            'desc'  => $resource->desc,
            'cards' => $resource->conditions->map(function (ConditionModel $item) {
                return [
                    'id'   => $item->id,
                    'name' => $item->vendorLocation->vendor->name,
                    'desc' => $item->vendorLocation->vendor->desc,
                    'type' => 'condition',
                ];
            }),
        ];
    }
}
