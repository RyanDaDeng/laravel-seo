<?php

namespace App\Modules\SeoAgent\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SeoMetaUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'draft_data' => 'json|required',
            'draft_data.title' => 'string|required',
            'draft_data.canonical' => 'string|required',
            'draft_data.description' => 'string|required'
        ];
    }

    public function attributes()
    {
        return [
            'draft_data.canonical' => 'canonical link',
            'draft_data.title' => 'title',
            'draft_data.description' => 'description',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }
}
