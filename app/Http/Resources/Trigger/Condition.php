<?php

namespace App\Http\Resources\Trigger;

use App\Http\Resources\Traits\ResourceTrait;
use App\Http\Resources\Vendor\Currency;
use App\Models\Trigger\Condition as ConditionModel;
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
        /** @var ConditionModel $resource */
        $resource = $this->resource;

        $response = [
            'id'                 => $resource->id,
            'group_id'           => $resource->group_id,
            'vendor_type_id'     => $resource->vendor_type_id,
            'vendor_location_id' => $resource->vendor_location_id,
            'parameters'         => $resource->parameters,
            'order_index'        => $resource->order_index,
            'is_enabled'         => $resource->is_enabled,
            'refreshed_at'       => $resource->refreshed_at,
            'changed_at'         => $resource->changed_at,
            'current_value'      => $resource->current_value,
        ];

        if ($resource->relationLoaded('group')) {
            $response = array_merge($response, [
                'group' => new Group($resource->group),
            ]);
        }

        if ($resource->relationLoaded('vendorType')) {
            $response = array_merge($response, [
                'vendorType' => new VendorType($resource->vendorType),
            ]);
        }

        if ($resource->relationLoaded('fromCurrency')) {
            // It's dynamic relation
            $response = array_merge($response, [
                'fromCurrency' => new Currency($resource->fromCurrency),
            ]);
        }

        if ($resource->relationLoaded('toCurrency')) {
            // It's dynamic relation
            $response = array_merge($response, [
                'toCurrency'   => new Currency($resource->toCurrency),
            ]);
        }

        if ($resource->relationLoaded('vendorLocation')) {
            $response = array_merge($response, [
                'vendorLocation' => new VendorLocation($resource->vendorLocation),
            ]);
        }

        return $response;
    }
}
