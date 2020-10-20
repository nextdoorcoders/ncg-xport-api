<?php

namespace App\Http\Requests\Vendor;

use App\Models\Vendor\MediaSync as MediaSyncModel;
use Illuminate\Foundation\Http\FormRequest;

class MediaSync extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'vendor_id'          => 'required|exists:' . MediaSyncModel::class . ',id',
            'vendor_location_id' => 'nullable',
            'source'             => 'required',
            'type'               => 'required',
            'value'              => 'required',
        ];
    }
}
