<?php

namespace App\Http\Requests\Trigger;

use Illuminate\Foundation\Http\FormRequest;

class ConditionRequest extends FormRequest
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
        $rules = [];

        $rules += match ($this->route()->getActionMethod()) {
            'createCondition' => [],
            'updateCondition' => [],
        };

        return $rules;
    }
}
