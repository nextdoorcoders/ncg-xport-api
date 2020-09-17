<?php

namespace App\Http\Resources\Trigger;

use App\Http\Resources\Traits\ResourceTrait;
use App\Models\Trigger\Condition as ConditionModel;
use App\Models\Vendor\Currency as CurrencyModel;
use App\Services\Vendor\Classes\Currency;
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
            'vendor_id'          => $resource->vendor_id,
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

        if ($resource->relationLoaded('vendor')) {
            $response = array_merge($response, [
                'vendor' => new Vendor($resource->vendor),
            ]);

            if ($resource->vendor->callback === Currency::class) {
                // TODO: Change DB structure and remove this shit!!!

                $fromCurrency = CurrencyModel::query()
                    ->where('id', $resource->parameters['from_currency_id'])
                    ->first();

                $toCurrency = CurrencyModel::query()
                    ->where('id', $resource->parameters['to_currency_id'])
                    ->first();

                $response = array_merge($response, [
                    'fromCurrency' => $fromCurrency,
                    'toCurrency'   => $toCurrency,
                ]);
            }
        }

        if ($resource->relationLoaded('vendorLocation')) {
            $response = array_merge($response, [
                'vendorLocation' => new VendorLocation($resource->vendorLocation),
            ]);
        }

        return $response;
    }
}
