<?php

namespace App\Http\Requests\Marketing;

use App\Models\Marketing\Account;
use App\Models\Marketing\Organization;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

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
        $rules = [
            'organization_id' => 'nullable|exists:' . Organization::class . ',id',
            'name'            => 'required|max:255',
            'is_enabled'      => 'boolean',
            'date_start_at'   => 'nullable|date|date_format:Y-m-d|before_or_equal:date_end_at',
            'date_end_at'     => 'nullable|date|date_format:Y-m-d|after_or_equal:date_start_at',
        ];

        switch ($this->method()) {
            case Request::METHOD_POST:
                $rules = array_merge($rules, [
                    'account_id'                 => 'required|exists:' . Account::class . ',id',
                    'parameters'                 => 'required|array',
                    'parameters.account_id'      => 'required|max:31',
                    'parameters.developer_token' => 'required|max:31',
                ]);
                break;
            case Request::METHOD_PUT:
                $rules = array_merge($rules, [
                    'account_id'                 => 'sometimes|exists:' . Account::class . ',id',
                    'parameters'                 => 'sometimes|array',
                    'parameters.account_id'      => 'sometimes|max:31',
                    'parameters.developer_token' => 'sometimes|max:31',
                ]);
                break;
        }

        return $rules;
    }

    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function messages()
    {
        return trans('marketing/project.validation');
    }
}
