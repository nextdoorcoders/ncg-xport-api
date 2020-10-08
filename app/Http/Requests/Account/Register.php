<?php

namespace App\Http\Requests\Account;

use App\Exceptions\MessageException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Register extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth('api')->guest();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     * @throws MessageException
     */
    public function rules()
    {
        return [
            'name'     => 'required',
            'email'    => [
                'required',
                'email',
                Rule::unique('account_users'),
            ],
            'password' => [
                'required',
                'between:8,32',
                'confirmed',
            ],
        ];
    }

    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function messages()
    {
        return trans('auth/register.validation');
    }
}
