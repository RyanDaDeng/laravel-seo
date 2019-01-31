<?php

namespace App\Modules\SeoAgent\Services;

use App\Modules\SeoAgent\Models\MetaSchemaEloquent;
use App\Modules\SeoAgent\Models\SeoAgentBaseModel;
use App\Modules\SeoAgent\Models\SeoAgentDraftData;
use App\Modules\SeoAgent\Repositories\SeoAgentRepository;
use Illuminate\Support\Facades\Log;


class SeoAgentService
{

    private $repository;

    public function __construct(SeoAgentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $per_page
     * @param $page
     * @param $orderBy
     * @param $orderDesc
     * @param null $wildSearch
     * @param null $type
     * @param null $status
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getDraftData($per_page, $page, $orderBy, $orderDesc, $wildSearch = null, $type = null, $status = null)
    {
        return $this->repository->getDraftMetaData($per_page, $page, $orderBy, $orderDesc, $wildSearch, $type,$status);
    }

    /**
     * @param $id
     * @return bool|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function deleteDraft($id)
    {
        if (!$this->repository->exists($id)) {
            return false;
        }
        return $this->repository->deleteDraft($id);
    }

    /**
     * @param $id
     * @param MetaSchemaEloquent $data
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function updateDraftData($id, MetaSchemaEloquent $data)
    {

        return $this->repository->updateDraftData($id, $data);
    }

    /**
     * @param $prepare
     * @return SeoAgentBaseModel|array
     */
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

    /**
     * @param $perPage
     * @param $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getOnlyDraftData($perPage, $page)
    {
        return $this->repository->getOnlyDraftData($perPage);
    }

    /**
     * @param $hash
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function getCurrentDataByHash($hash)
    {
        return $this->repository->getCurrentDataByHash($hash);
    }

    /**
     * @param $hash
     * @param MetaSchemaEloquent $data
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|null|object
     */
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


    /**
     * @param $metaId
     * @return SeoAgentRepository[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getHistoryByMetaHash($metaId)
    {
        return $this->repository->getHistoryByMetaHash($metaId);

    }

    /**
     * @param $hash
     * @param $comments
     * @param $status
     * @return bool
     */
    public function updateStatus($hash, $comments, $status)
    {
        // create history first
        /**
         * @var SeoAgentDraftData $meta
         */
        $meta = $this->repository->getDraftDataByHash($hash);

        if ($meta) {
            return $this->repository->createHistory($meta, $comments, $status);
        } else {
            return false;
        }
    }


}

