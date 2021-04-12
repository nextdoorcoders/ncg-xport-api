<?php

namespace App\Http\Requests\Marketing;

use Illuminate\Foundation\Http\FormRequest;

class Campaing extends FormRequest
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
            'rate_min' => 'required_with:is_rate_enabled|numeric|between:0,99999999.99|lt:rate_max',
            'rate_max' => 'required_with:is_rate_enabled|numeric|between:0,99999999.99|gt:rate_min',
        ];
    }
}
