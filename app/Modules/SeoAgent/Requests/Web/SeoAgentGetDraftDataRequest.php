<?php

namespace App\Modules\SeoAgent\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;


class SeoAgentGetDraftDataRequest extends FormRequest 
{


    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
          'per_page'=>'integer',
          'page'=>'integer'
      ];
    }

}

