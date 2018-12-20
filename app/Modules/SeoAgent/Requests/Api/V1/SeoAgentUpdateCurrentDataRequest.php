<?php

namespace App\Modules\SeoAgent\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class SeoAgentUpdateCurrentDataRequest extends FormRequest
{


    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'string|nullable',
            'description' => 'string|nullable',
            'canonical' => 'string|nullable',
            'keywords' => 'array|nullable',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

}

