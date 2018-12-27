<?php

namespace App\Modules\Setting\Services;

use App\Modules\Setting\Repositories\SettingRepository;


class SettingService
{

    private $repository;

    public function __construct(SettingRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getPushSettings()
    {
        return $this->repository->getPushSetting();
    }

    public function updatePushSettings($data)
    {
        return $this->repository->updatePushSetting($data);
    }
    public function updatePullSettings($data)
    {
        return $this->repository->updatePullSettings($data);
    }


    public function getPullSettings()
    {
        return $this->repository->getPullSetting();
    }

    public function getAllSettings()
    {
        return $this->repository->getAllSettings();
    }
}

