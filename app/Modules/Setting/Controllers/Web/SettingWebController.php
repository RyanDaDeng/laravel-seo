<?php

namespace App\Modules\Setting\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\Setting\Facades\SettingService;
use App\Modules\Setting\Requests\Web\SettingGetAllSettingsRequest;


class SettingWebController extends Controller 
{


    public function getAllSettings(SettingGetAllSettingsRequest $request )
    {
        $request->validated();
        return SettingService::getAllSettings();
    }


}

