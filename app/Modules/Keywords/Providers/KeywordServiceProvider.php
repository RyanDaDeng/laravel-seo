<?php

namespace App\Modules\Keywords\Providers;

use App\Modules\SeoAgent\Services\KeywordService;
use App\Modules\Shared\Providers\ModularServiceProvider;


class KeywordServiceProvider extends ModularServiceProvider
{
    public function register()
    {
        $this->app->singleton(
            'KeywordService',
            KeywordService::class
        );
    }

}

