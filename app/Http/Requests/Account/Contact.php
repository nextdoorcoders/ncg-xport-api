<?php

namespace App\Http\Requests\Account;

use Illuminate\Foundation\Http\FormRequest;

class Contact extends FormRequest
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
            'type'  => 'required',
            'value' => 'required',
        ];
    }

    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function messages()
    {
        return trans('auth/contact.validation');
    }
}
