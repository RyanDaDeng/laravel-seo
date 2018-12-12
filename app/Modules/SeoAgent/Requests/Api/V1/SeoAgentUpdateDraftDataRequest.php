<?php

namespace App\Modules\SeoAgent\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class SeoAgentUpdateDraftDataRequest extends FormRequest 
{


    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
          'draft_data'=>'json|required',
          'draft_data.meta.defaults.title'=>'string|nullable',
          'draft_data.meta.defaults.description'=>'string|nullable',
          'draft_data.meta.defaults.canonical'=>'string|nullable'
      ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json($validator->errors(), 422));
    }

}

