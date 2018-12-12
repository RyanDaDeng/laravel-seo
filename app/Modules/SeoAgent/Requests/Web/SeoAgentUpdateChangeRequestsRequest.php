<?php

namespace App\Modules\SeoAgent\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;


class SeoAgentUpdateChangeRequestsRequest extends FormRequest 
{


    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
          'current_data'=>'json',
          'is_approved'=>'boolean'
      ];
    }

}

