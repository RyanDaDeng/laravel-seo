<?php

namespace App\Modules\SeoAgent\Providers;

use App\Modules\SeoAgent\Services\SeoAgentService;
use App\Modules\Shared\Providers\ModularServiceProvider;


class SeoAgentServiceProvider extends ModularServiceProvider
{

    public function register()
    {
       $this->app->singleton(
           'SeoAgentService',
            SeoAgentService::class
        );
   }
}

