<?php

namespace App\Modules\Setting\Contracts;


interface SettingServiceInterface
{
    public function getPushSettings();

    public function getPullSettings();

    public function getAllSettings();

}

