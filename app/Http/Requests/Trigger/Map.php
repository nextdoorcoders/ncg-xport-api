<?php

namespace App\Http\Requests\Trigger;

use Illuminate\Foundation\Http\FormRequest;

class Map extends FormRequest
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
            'name'    => 'required',
            'city_id' => 'required',
        ];
    }
}
