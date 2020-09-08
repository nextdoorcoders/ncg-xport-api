<?php

namespace App\Http\Resources\Marketing;

use App\Models\Marketing\Condition as ConditionModel;
use App\Models\Marketing\Group as GroupModel;
use App\Models\Marketing\VendorLocation as VendorLocationModel;
use App\Models\Traits\UuidTrait;
use Illuminate\Http\Resources\Json\JsonResource;

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

        return $resource->map(function ($model) {
            if ($model instanceof VendorLocationModel) {
                /** @var VendorLocationModel $model */
                return [
                    'id'         => $model->id,
                    'vendor_id'  => $model->vendor_id,
                    'name'       => $model->vendor->name,
                    'parameters' => $model->vendor->default_parameters,
                ];
            } else {
                /** @var GroupModel $model */
                return [
                    'id'         => $model->id,
                    'name'       => $model->name,
                    'desc'       => $model->desc,
                    'type'       => 'group',
                    'conditions' => $model->conditions->map(function (ConditionModel $condition) {
                        return [
                            'id'            => $condition->id,
                            'group_id'      => $condition->group_id,
                            'parameters'    => $condition->parameters,
                            'name'          => $condition->vendorLocation->vendor->name,
                            'desc'          => $condition->vendorLocation->vendor->desc,
                            'type'          => 'condition',
                            'settings'      => $condition->vendorLocation->vendor->settings,
                            'current_value' => $condition->current_value,
                            'is_enabled'    => $condition->is_trigger_enabled,
                        ];
                    }),
                ];
            }
        });
    }
}
