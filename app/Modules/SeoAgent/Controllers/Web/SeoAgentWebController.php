<?php

namespace App\Modules\SeoAgent\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\SeoAgent\Facades\SeoAgentService;
use App\Modules\SeoAgent\Models\MetaSchemaEloquent;
use App\Modules\SeoAgent\Requests\Web\SeoAgentGetDraftDataRequest;
use App\Modules\SeoAgent\Requests\Web\SeoAgentUpdateDraftDataRequest;


class SeoAgentWebController extends Controller
{


    public function getDraftData(SeoAgentGetDraftDataRequest $request)
    {
        $request->validated();
        return SeoAgentService::getDraftData(
            $request->query('per_page'),
            $request->query('page'),
            $request->query('order_by'),
            $request->query('order_desc'),
            $request->query('wild_search'),
            $request->query('type')
        );
    }


    public function updateDraftData(SeoAgentUpdateDraftDataRequest $request, $id)
    {
        $request->validated();
        $data = $request->only(['title', 'description', 'keywords', 'canonical']);

        $schema = new MetaSchemaEloquent();
        $schema->fill($data);

        return SeoAgentService::updateDraftData($id, $schema);
    }

    public function deleteDraftData($id)
    {
        return SeoAgentService::deleteDraft($id);

    }


}

