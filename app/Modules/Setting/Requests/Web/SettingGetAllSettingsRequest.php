<?php

namespace App\Modules\Setting\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;


class SettingGetAllSettingsRequest extends FormRequest 
{


    
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
      ];
    }

}

