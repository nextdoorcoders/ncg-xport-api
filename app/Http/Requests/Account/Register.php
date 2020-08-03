<?php

namespace App\Http\Requests\Account;

use App\Exceptions\MessageException;
use Illuminate\Foundation\Http\FormRequest;

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
        return [];
    }
}
