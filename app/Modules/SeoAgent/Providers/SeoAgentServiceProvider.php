<?php

namespace App\Modules\SeoAgent\Providers;

use App\Modules\SeoAgent\Contracts\SeoAgentServiceInterface;
use App\Modules\SeoAgent\Services\SeoAgentService;
use Illuminate\Support\ServiceProvider;


class SeoAgentServiceProvider extends ServiceProvider 
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
           'SeoAgentService',
            SeoAgentService::class
        );
   }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
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
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }
}

