<?php

namespace App\Http\Resources\Trigger;

use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Trigger\Condition as ConditionModel;
use App\Models\Trigger\Group as GroupModel;
use App\Models\Trigger\VendorLocation as VendorLocationModel;
use Illuminate\Http\Resources\Json\JsonResource;

class Map extends JsonResource
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
//        $resource = $this->resource;
//
//        return $resource->map(function ($model) {
//            if ($model instanceof VendorLocationModel) {
//                /** @var VendorLocationModel $model */
//                return [
//                    'id'         => $model->id,
//                    'vendor_id'  => $model->vendor_id,
//                    'name'       => $model->vendor->name,
//                    'parameters' => $model->vendor->default_parameters,
//                ];
//            } else {
//                /** @var GroupModel $model */
//                return [
//                    'id'         => $model->id,
//                    'name'       => $model->name,
//                    'desc'       => $model->desc,
//                    'type'       => 'group',
//                    'conditions' => $model->conditions->map(function (ConditionModel $condition) {
//                        return [
//                            'id'            => $condition->id,
//                            'group_id'      => $condition->group_id,
//                            'parameters'    => $condition->parameters,
//                            'name'          => $condition->vendor->vendor->name,
//                            'desc'          => $condition->vendor->vendor->desc,
//                            'type'          => 'condition',
//                            'settings'      => $condition->vendor->vendor->settings,
//                            'current_value' => $condition->current_value,
//                            'is_enabled'    => $condition->is_enabled,
//                        ];
//                    }),
//                ];
//            }
//        });

        return parent::toArray($request);
    }
}
