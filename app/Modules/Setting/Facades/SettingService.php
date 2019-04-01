<?php

namespace App\Modules\Setting\Facades;

use App\Modules\Setting\Contracts\SettingServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Collection getPushSettings()
 * @method static Collection getPullSettings()
 * @method static Collection getAllSettings()
 * @method static Collection getGoogleSettings()
 * @method static Collection updatePushSettings($data)
 * @method static Collection updatePullSettings($data)
 * @method static Collection updateGoogleSetting($data)
 */
class SettingService extends Facade 
{


    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return SettingServiceInterface::class;
    }

}

