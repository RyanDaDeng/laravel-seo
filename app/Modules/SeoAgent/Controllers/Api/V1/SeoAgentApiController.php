<?php

namespace App\Modules\SeoAgent\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Modules\SeoAgent\Facades\SeoAgentService;
use App\Modules\SeoAgent\Requests\Api\V1\SeoAgentGetDraftDataRequest;
use App\Modules\SeoAgent\Requests\Api\V1\SeoAgentUpdateDraftDataRequest;
use App\Modules\SeoAgent\Requests\Api\V1\SeoAgentGetChangeRequestsRequest;
use App\Modules\SeoAgent\Requests\Api\V1\SeoAgentUpdateChangeRequestsRequest;
use App\Modules\SeoAgent\Requests\Api\V1\SeoAgentCreateChangeRequestsRequest;
use App\Modules\SeoAgent\Requests\Api\V1\SeoAgentBulkUpdateOrInsertChangeRequestsRequest;


class SeoAgentApiController extends Controller 
{


    public function getDraftData(SeoAgentGetDraftDataRequest $request )
    {
        $request->validated();
        return SeoAgentService::getDraftData($request->query('per_page'),$request->query('page'));
    }


    public function updateDraftData(SeoAgentUpdateDraftDataRequest $request ,$id)
    {
        $request->validated();
        return SeoAgentService::updateDraftData($id, $request->all());
    }


    public function getChangeRequests(SeoAgentGetChangeRequestsRequest $request )
    {
        $request->validated();
        return SeoAgentService::getChangeRequests($request->query('per_page'),$request->query('page'));
    }


    public function updateChangeRequests(SeoAgentUpdateChangeRequestsRequest $request ,$hash_id)
    {
        $request->validated();
        return SeoAgentService::updateChangeRequests($hash_id, $request->all());
    }


    public function createChangeRequests(SeoAgentCreateChangeRequestsRequest $request )
    {
        $request->validated();
        return SeoAgentService::createChangeRequests( $request->all());
    }


    public function bulkUpdateOrInsertChangeRequests(SeoAgentBulkUpdateOrInsertChangeRequestsRequest $request )
    {
        $request->validated();
        return SeoAgentService::bulkUpdateOrInsertChangeRequests( $request->all());
    }


}

