<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 7/12/18
 * Time: 12:06 PM
 */

namespace App\Modules\SeoAgent\Providers;

use App\Modules\SeoAgent\Repositories\Interfaces\SeoAgentRepositoryInterface;
use App\Modules\SeoAgent\Repositories\SeoAgentRepository as SeoAgentRepository;
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

    }

    public function register()
    {

        $this->app->singleton(
            SeoAgentRepositoryInterface::class,
            SeoAgentRepository::class
        );
    }
}
