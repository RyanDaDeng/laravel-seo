<?php

namespace App\Modules\SeoAgent\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;


class SeoAgentUpdateDraftDataRequest extends FormRequest
{


    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'title' => 'string|required',
            'description' => 'string|required',
            'keywords' => 'array'
        ];
    }

}

