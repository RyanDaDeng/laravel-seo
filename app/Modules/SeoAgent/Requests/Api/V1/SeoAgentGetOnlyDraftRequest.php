<?php

namespace App\Modules\SeoAgent\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class SeoAgentGetOnlyDraftRequest extends FormRequest
{


    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'per_page' => 'integer|required',
            'page' => 'integer|required'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

}

