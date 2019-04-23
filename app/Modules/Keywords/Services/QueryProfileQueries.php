<?php

namespace App\Modules\Keywords\Services;

use App\Modules\DataMigration\Services\SnapshotSummaryService;
use App\Modules\Keywords\Models\Keyword;
use App\Modules\Keywords\Models\Page;
use App\Modules\Keywords\Models\QueryDetails;
use App\Modules\Keywords\Models\QueryProfile;
use App\Modules\SeoAgent\Models\SeoAgentCurrentData;
use Carbon\Carbon;

class QueryProfileQueries
{
    public static function getById($pageId, $keywordId)
    {
        $page = Page::query()->where('id', $pageId)->first();
        $keyword = Keyword::query()->where('id', $keywordId)->first();
        if ($page && $keyword) {
            $obj = QueryProfile::query()->where('page', $pageId)->where('keyword', $keywordId)->first();
            if (!$obj) {
                $obj = new QueryProfile();
                $obj->ctr_benchmark = 0;
                $obj->click_potential = 0;
                $obj->is_primary = 0;
                $obj->page = $pageId;
                $obj->keyword = $keywordId;
                $obj->save();
                return $obj;
            }
            return $obj;
        }
        return null;
    }
}

