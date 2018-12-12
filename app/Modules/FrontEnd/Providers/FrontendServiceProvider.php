<?php

namespace App\Modules\FrontEnd\Providers;

use Illuminate\Support\ServiceProvider;


class FrontendServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'ui');
        $this->loadRoutesFrom(__DIR__ . '/../routes/routes.php');
    }

    public function register()
    {
    }

}

