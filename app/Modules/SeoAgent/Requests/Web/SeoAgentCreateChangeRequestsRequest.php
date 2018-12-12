<?php

namespace App\Modules\SeoAgent\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;


class SeoAgentCreateChangeRequestsRequest extends FormRequest 
{


    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
          'current_data'=>'json|required',
          'current_data.meta.defaults.title'=>'string|nullable',
          'current_data.meta.defaults.description'=>'string|nullable',
          'current_data.meta.defaults.canonical'=>'string|nullable',
          'path'=>'string|required',
          'hash'=>'string|required'
      ];
    }

}

