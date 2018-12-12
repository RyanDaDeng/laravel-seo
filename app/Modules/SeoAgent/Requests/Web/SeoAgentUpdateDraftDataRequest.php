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
          'draft_data'=>'json|required',
          'draft_data.meta.defaults.title'=>'string|nullable',
          'draft_data.meta.defaults.description'=>'string|nullable',
          'draft_data.meta.defaults.canonical'=>'string|nullable'
      ];
    }

}

