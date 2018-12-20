<?php

namespace App\Modules\SeoAgent\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class SeoAgentCreateCurrentDataRequest extends FormRequest
{


    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'current_data' => 'required',
            'current_data.title' => 'string|nullable',
            'current_data.description' => 'string|nullable',
            'current_data.canonical' => 'string|nullable',
            'current_data.keywords' => 'array|nullable',
            'path' => 'string|required',
            'hash' => 'string|required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

}

