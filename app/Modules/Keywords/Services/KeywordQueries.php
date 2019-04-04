<?php

namespace App\Modules\Keywords\Services;

use App\Modules\Keywords\Models\Keyword;
use App\Modules\Keywords\Models\Page;
use App\Modules\Keywords\Models\QueryDetails;
use App\Modules\Keywords\Models\QueryProfile;
use App\Modules\SeoAgent\Models\SeoAgentCurrentData;
use Carbon\Carbon;

class KeywordQueries
{

    public static function getFilterOperand($filter, $url)
    {
        switch ($filter) {
            case 'contains':
                return ['like', "%$url%"];
            case 'does_not_contain':
                return ['not like', "%$url%"];
            case 'is_exactly':
                return ['=', $url];
            default:
                return ['=', $url];

        }
    }

    public static function getFilteredPages($urlFilter, $url)
    {
        $data = self::getFilterOperand($urlFilter, $url);

        return Page::query()->where('shortcut_path', $data[0], $data[1])->get();
    }


    public static function getFilteredKeywords($keywordFilter, $keyword)
    {
        $data = self::getFilterOperand($keywordFilter, $keyword);

        return Keyword::query()->where('keyword', $data[0], $data[1])->get();
    }


    public static function getKeywordList($aDateFrom,
                                          $aDateTo,
                                          $device,
                                          $url,
                                          $urlFilter,
                                          $keyword,
                                          $keywordFilter,
                                          $sortBy,
                                          $sortOrder,
                                          $perPage,
                                          $pathMd5,
                                          $isPrimary)
    {

        $page = null;
        if ($pathMd5) {
            $page = Page::query()->where('path_md5', $pathMd5)->first();
        }

        $aDateFrom = Carbon::parse($aDateFrom);
        $aDateTo = Carbon::parse($aDateTo);

        $query = \DB::table('tbl_gw_query_details')
            ->selectRaw(
                '	tbl_gw_query_details.index as index_id,
                tbl_gw_query_details.page as page_id,
                tbl_gw_query_details.keyword as keyword_id,
	sum(tbl_gw_query_details.clicks) as sum_clicks,
	round(sum(tbl_gw_query_details.impressions),2) as sum_impressions, 
	round(sum(tbl_gw_query_details.position),2) as sum_positions,
	round(sum(tbl_gw_query_details.average_weight_ranking),2) as sum_average_weight_ranking,
    round(sum(tbl_gw_query_details.clicks)/sum(tbl_gw_query_details.impressions),4) as avg_ctr')
            ->whereBetween('date', [$aDateFrom->format('Y-m-d'), $aDateTo->format('Y-m-d')]);

        $differInDay = $aDateFrom->diffInDays($aDateTo) + 1;

        // is primary filter
        if (!empty($isPrimary) && $isPrimary == '1') {
            $query = $query->join('tbl_gw_query_profiles', 'tbl_gw_query_details.index', '=', 'tbl_gw_query_profiles.index')
                ->where('tbl_gw_query_profiles.is_primary','=',1);
        }

        // device filter
        if (!empty($device)) {
            $query = $query->where('tbl_gw_query_details.device', '=', QueryDetails::getDeviceTypeByName($device));
        }

        // md5 filter
        if ($page) {
            $query = $query->where('tbl_gw_query_details.page', '=', $page->id);
        }
        // url filter
        $urlData = [];
        if (!empty($url) && !empty($urlFilter)) {
            $urlData = self::getFilteredPages($urlFilter, $url);
            $query = $query->whereIn('tbl_gw_query_details.page', $urlData->pluck('id'));
        }


        // keyword filter
        $keywordData = [];
        if (!empty($keyword) && !empty($keywordFilter)) {
            $keywordData = self::getFilteredKeywords($keywordFilter, $keyword);
            $query = $query->whereIn('tbl_gw_query_details.keyword', $keywordData->pluck('id'));
        }

        // sort filter
        if ($sortBy) {
            $sortOrder = $sortOrder === 'asc' ? 'asc' : 'desc';
            $query = $query->orderBy($sortBy, $sortOrder);
        }

        $res = $query->groupBy(['tbl_gw_query_details.index','tbl_gw_query_details.page','tbl_gw_query_details.keyword'])->paginate($perPage);

        $keywordIds = $res->getCollection()->pluck('keyword_id')->unique();
        $pageIds = $res->getCollection()->pluck('page_id')->unique();
        $profileIds = $res->getCollection()->pluck('index_id')->unique();
        $keywordMaps = !empty($keywordData) ? $keywordData->pluck('keyword', 'id') : Keyword::query()->whereIn('id', $keywordIds)->pluck('keyword', 'id');
        $pages = !empty($urlData) ? $urlData : Page::query()->whereIn('id', $pageIds)->get();
        $metaMaps = SeoAgentCurrentData::query()->whereIn('hash', $pages->pluck('path_md5'))->get()->keyBy('hash');
        $pageMaps = $pages->keyBy('id')->toArray();
        $keyMaps = [];
        $profileMaps = QueryProfile::query()->whereIn('index', $profileIds)->select('is_primary','ctr_benchmark','click_potential','id','index')->get()->keyBy('index');
        $res->getCollection()->transform(function ($row) use ($keywordMaps, $pageMaps, $differInDay, &$keyMaps, $metaMaps,$profileMaps) {
            // join our seo meta url object for each keyword
            if (isset($pageMaps[$row->page_id])) {
                $row->page = $pageMaps[$row->page_id]['url'];
                if (isset($metaMaps[$pageMaps[$row->page_id]['path_md5']])) {
                    $row->meta = $metaMaps[$pageMaps[$row->page_id]['path_md5']];
                    $row->path_md5 = $metaMaps[$pageMaps[$row->page_id]['path_md5']]['hash'];
                } else {
                    $row->meta = null;
                    $row->path_md5 = null;
                }
            }

            // check if keyword exists
            if (isset($keywordMaps[$row->keyword_id])) {
                $row->keyword = $keywordMaps[$row->keyword_id];
            }

            // re-format the data number and calculate extra average
            $row->avg_ctr = round($row->avg_ctr, 4);
            $row->avg_positions = round($row->sum_average_weight_ranking / $row->sum_impressions, 4);

            if(isset($profileMaps[$row->index_id])){
                $row->profile = $profileMaps[$row->index_id];
            }else{
                $row->profile = null;
            }
            // put hash mapping into maps for the reference of page+keyword
            $keyMaps[$row->index_id] = (array)$row;
            return (array)$row;
        });

        return [$keyMaps, $res->toArray()];
    }


}

