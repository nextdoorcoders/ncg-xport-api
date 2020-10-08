<?php

namespace App\Http\Requests\Trigger;

use App\Models\Marketing\Project as ProjectModel;
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
            'project_id'     => 'nullable|exists:' . ProjectModel::class . ',id',
            'name'           => 'required|max:255',
            'description'    => 'nullable|max:1023',
            'shutdown_delay' => 'integer|between:0,3600',
        ];
    }

    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    public function messages()
    {
        return trans('trigger/map.validation');
    }
}
