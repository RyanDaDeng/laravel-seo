<?php

namespace App\Modules\SeoAgent\Services;

use App\Modules\SeoAgent\Contracts\SeoAgentServiceInterface;
use App\Modules\SeoAgent\Repositories\SeoAgentRepository;


class SeoAgentService implements SeoAgentServiceInterface
{

    private $repository;

    public function __construct(SeoAgentRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDraftData($per_page, $page)
    {
        return $this->repository->getDraftMetaData($per_page);
    }

    public function updateDraftData($id, $data = [])
    {
        return $this->repository->updateDraftData($id, $data);
    }

    public function getChangeRequests($per_page, $page)
    {
        // TODO: Implement getChangeRequests() method.
    }

    public function updateChangeRequests($hash_id, $data = [])
    {
        // TODO: Implement updateChangeRequests() method.
    }

    public function createChangeRequests($data = [])
    {
        // TODO: Implement createChangeRequests() method.
    }

    public function bulkUpdateOrInsertChangeRequests($data = [])
    {
        // TODO: Implement bulkUpdateOrInsertChangeRequests() method.
    }
}

