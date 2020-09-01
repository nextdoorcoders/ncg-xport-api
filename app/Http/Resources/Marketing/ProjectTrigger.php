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
                'type'  => 'vendor',
                'cards' => $resource->vendorsLocation->map(function (VendorLocationModel $vendorLocation) {
                    return [
                        'id'        => $vendorLocation->id,
                        'vendor_id' => $vendorLocation->vendor_id,
                        'name'      => $vendorLocation->vendor->name,
                        'desc'      => $vendorLocation->vendor->desc,
                        'type'      => 'vendorLocation',
                    ];
                }),
            ];
        }

        return $resource->map(function ($group) {
            /** @var GroupModel $group */
            return [
                'id'    => $group->id,
                'name'  => $group->name,
                'desc'  => $group->desc,
                'type'  => 'group',
                'cards' => $group->conditions->map(function (ConditionModel $condition) {
                    return [
                        'id'         => $condition->id,
                        'group_id'   => $condition->group_id,
                        'parameters' => $condition->parameters,
                        'name'       => $condition->vendorLocation->vendor->name,
                        'desc'       => $condition->vendorLocation->vendor->desc,
                        'type'       => 'condition',
                    ];
                }),
            ];
        });
    }
}
