<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\SeoAgent\Facades\SeoAgentRepository;
use App\Modules\SeoAgent\Requests\SeoMetaGetMetaDataRequest;
use App\Modules\SeoAgent\Requests\SeoMetaUpdateRequest;

class SeoAgentController extends Controller
{

    public function getMetaDatById($id)
    {
        return SeoAgentRepository::getSeoMetaById($id);
    }

    public function getMetaData(SeoMetaGetMetaDataRequest $request)
    {
        $request->validated();

        return SeoAgentRepository::getSeoMeta(
            $request->query('per_page'),
            $request->query('order_by')
        );
    }


    public function updateMetaData(SeoMetaUpdateRequest $request, $id)
    {

        $request->validated();

        return SeoAgentRepository::updateSeoMeta(
            $id,
            $request->only('draft_data')
        );
    }

}
