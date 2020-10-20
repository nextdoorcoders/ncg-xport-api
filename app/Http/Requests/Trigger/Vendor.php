<?php

namespace App\Http\Requests\Trigger;

use Illuminate\Foundation\Http\FormRequest;

class Vendor extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'               => 'required',
            'callback'           => 'required',
            'vendor_type'        => 'required',
            'type'               => 'required',
            'default_parameters' => 'sometimes',
            'settings'           => 'sometimes',
        ];
    }

    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function messages()
    {
        return trans('trigger/vendor.validation');
    }
}
