<?php

namespace App\Http\Requests\Marketing;

use Illuminate\Foundation\Http\FormRequest;

class Project extends FormRequest
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
            'account_id'                 => 'nullable',
            'organization_id'            => 'nullable',
            'map_id'                     => 'nullable',
            'name'                       => 'required',
            'parameters'                 => 'required|array',
            'parameters.account_id'      => 'nullable',
            'parameters.developer_token' => 'nullable',
            'date_start_at'              => 'required|date|date_format:Y-m-d|before:date_end_at',
            'date_end_at'                => 'required|date|date_format:Y-m-d|after:date_start_at',
        ];
    }
}
