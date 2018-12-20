<?php

namespace App\Modules\SeoAgent\Services;

use App\Modules\SeoAgent\Models\MetaSchemaEloquent;
use App\Modules\SeoAgent\Models\SeoAgentBaseModel;
use App\Modules\SeoAgent\Repositories\SeoAgentRepository;
use Illuminate\Support\Facades\Log;


class SeoAgentService
{

    private $repository;

    public function __construct(SeoAgentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDraftData($per_page, $page, $orderBy, $orderDesc, $wildSearch = null, $type = null)
    {
        return $this->repository->getDraftMetaData($per_page, $page, $orderBy, $orderDesc, $wildSearch, $type);
    }

    public function deleteDraft($id)
    {
        if (!$this->repository->exists($id)) {
            return false;
        }
        return $this->repository->deleteDraft($id);
    }


    public function updateDraftData($id, MetaSchemaEloquent $data)
    {

        return $this->repository->updateDraftData($id, $data);
    }


    public function createCurrentData($prepare)
    {
        try {
            if ($this->repository->existsByHash($prepare['hash'])) {
                return [];
            }
            return $this->repository->createCurrentData($prepare);
        } catch (\Exception $e) {
            Log::error($e);
            return [];
        }
    }

    public function getOnlyDraftData($perPage, $page)
    {
        return $this->repository->getOnlyDraftData($perPage);
    }


    public function getCurrentDataByHash($hash)
    {
        return $this->repository->getCurrentDataByHash($hash);
    }


    public function updateCurrentDataByHash($hash, MetaSchemaEloquent $data)
    {
        try {
            if (!$this->repository->existsByHash($hash)) {
                return [];
            }
            return $this->repository->updateCurrentDataByHash($hash, $data);
        } catch (\Exception $e) {
            Log::error($e);
            return [];
        }
    }
}

