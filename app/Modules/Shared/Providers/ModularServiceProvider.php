<?php

namespace App\Modules\Shared\Providers;

use Illuminate\Support\ServiceProvider;


abstract class ModularServiceProvider extends ServiceProvider
{
    /**
     * @throws \ReflectionException
     */
    public function boot()
    {
        $this->map();
    }


    /**
     * @throws \ReflectionException
     */
    public function map()
    {
        $this->mapApiRoutes();
        $this->mapWebRoutes();
    }

    /**
     * @throws \ReflectionException
     */
    protected function mapWebRoutes()
    {
        $this->loadRoutesFrom($this->getDir() . '/../routes/web.php');
    }

    /**
     * @throws \ReflectionException
     */
    protected function mapApiRoutes()
    {
        $this->loadRoutesFrom($this->getDir() . '/../routes/api.php');
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    protected function getDir() {
        $reflector = new \ReflectionClass(get_class($this));
        $filename = $reflector->getFileName();
        return dirname($filename);
    }
}

