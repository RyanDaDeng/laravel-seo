<?php

namespace App\Modules\Setting\Providers;

use App\Modules\Setting\Contracts\SettingServiceInterface;
use App\Modules\Setting\Services\SettingService;
use Illuminate\Support\ServiceProvider;


class SettingServiceProvider extends ServiceProvider 
{


    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->map();
    }

    public function register()
    {
       $this->app->singleton(
            SettingServiceInterface::class,
            SettingService::class
        );
    }
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes//api.php');
    }

}

