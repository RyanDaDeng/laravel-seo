<?php

namespace App\Modules\SeoAgent\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\SeoAgent\Facades\SeoAgentService;
use App\Modules\SeoAgent\Requests\Web\SeoAgentGetDraftDataRequest;
use App\Modules\SeoAgent\Requests\Web\SeoAgentUpdateDraftDataRequest;
use App\Modules\SeoAgent\Requests\Web\SeoAgentGetChangeRequestsRequest;
use App\Modules\SeoAgent\Requests\Web\SeoAgentUpdateChangeRequestsRequest;
use App\Modules\SeoAgent\Requests\Web\SeoAgentCreateChangeRequestsRequest;
use App\Modules\SeoAgent\Requests\Web\SeoAgentBulkUpdateOrInsertChangeRequestsRequest;


class SeoAgentWebController extends Controller
{


    public function getDraftData(SeoAgentGetDraftDataRequest $request)
    {
        $request->validated();
        return SeoAgentService::getDraftData($request->query('per_page'), $request->query('page'));
    }


    public function updateDraftData(SeoAgentUpdateDraftDataRequest $request, $id)
    {
        $request->validated();
        return SeoAgentService::updateDraftData($id, $request->all());
    }


    public function getChangeRequests(SeoAgentGetChangeRequestsRequest $request)
    {
        $request->validated();
        return SeoAgentService::getChangeRequests($request->query('per_page'), $request->query('page'));
    }


    public function updateChangeRequests(SeoAgentUpdateChangeRequestsRequest $request, $hash_id)
    {
        $request->validated();
        return SeoAgentService::updateChangeRequests($hash_id, $request->all());
    }


    public function createChangeRequests(SeoAgentCreateChangeRequestsRequest $request)
    {
        $request->validated();
        return SeoAgentService::createChangeRequests($request->all());
    }


    public function bulkUpdateOrInsertChangeRequests(SeoAgentBulkUpdateOrInsertChangeRequestsRequest $request)
    {
        $request->validated();
        return SeoAgentService::bulkUpdateOrInsertChangeRequests($request->all());
    }


}

