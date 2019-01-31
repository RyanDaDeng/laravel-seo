<?php

namespace App\Modules\SeoAgent\Facades;

use Illuminate\Support\Facades\Facade;


class SeoAgentService extends Facade
{


    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'SeoAgentService';
    }

}

