<?php

namespace App\Modules\SeoAgent\Contracts;


interface SeoAgentServiceInterface
{
    public function getDraftData($per_page,$page);

    public function updateDraftData($id,$data =[]);

    public function getChangeRequests($per_page,$page);

    public function updateChangeRequests($hash_id,$data =[]);

    public function createChangeRequests($data =[]);

    public function bulkUpdateOrInsertChangeRequests($data =[]);

}

