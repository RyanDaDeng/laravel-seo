<?php

namespace App\Modules\Keywords\Services;

use App\Modules\Keywords\Models\Keyword;
use App\Modules\Keywords\Models\Page;
use App\Modules\Keywords\Models\QueryDetails;
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
                                          $pathMd5)
    {

        $page = null;
        if ($pathMd5) {
            $page = Page::query()->where('path_md5', $pathMd5)->first();
        }

        $aDateFrom = Carbon::parse($aDateFrom);
        $aDateTo = Carbon::parse($aDateTo);

        $query = \DB::table('tbl_gw_query_details')
            ->selectRaw(
                '	page as page_id,
                keyword as keyword_id,
	sum(clicks) as sum_clicks,
	round(sum(impressions),2) as sum_impressions, 
	round(sum(position),2) as sum_positions,
    round(sum(clicks)/sum(impressions),2) as avg_ctr')
            ->whereBetween('date', [$aDateFrom->format('Y-m-d'), $aDateTo->format('Y-m-d')]);

        $differInDay = $aDateFrom->diffInDays($aDateTo) + 1;

        // device filter
        if (!empty($device)) {
            $query = $query->where('device', '=', QueryDetails::getDeviceTypeByName($device));
        }

        // md5 filter
        if($page){
            $query = $query->where('page', '=', $page->id);
        }
        // url filter
        $urlData = [];
        if (!empty($url) && !empty($urlFilter)) {
            $urlData = self::getFilteredPages($urlFilter, $url);
            $query = $query->whereIn('page', $urlData->pluck('id'));
        }


        // keyword filter
        $keywordData = [];
        if (!empty($keyword) && !empty($keywordFilter)) {
            $keywordData = self::getFilteredKeywords($keywordFilter, $keyword);
            $query = $query->whereIn('keyword', $keywordData->pluck('id'));
        }

        // sort filter
        if ($sortBy) {
            $sortOrder = $sortOrder === 'asc' ? 'asc' : 'desc';
            $query = $query->orderBy($sortBy, $sortOrder);
        }

        $res = $query->groupBy(['page', 'keyword'])->paginate($perPage);


        $keywordIds = $res->getCollection()->pluck('keyword_id')->unique();
        $pageIds = $res->getCollection()->pluck('page_id')->unique();

        $keywordMaps = !empty($keywordData) ? $keywordData->pluck('keyword', 'id') : Keyword::query()->whereIn('id', $keywordIds)->pluck('keyword', 'id');
        $pages = !empty($urlData) ? $urlData : Page::query()->whereIn('id', $pageIds)->get();
        $metaMaps = SeoAgentCurrentData::query()->whereIn('hash', $pages->pluck('path_md5'))->get()->keyBy('hash');
        $pageMaps = $pages->keyBy('id')->toArray();
        $keyMaps = [];
        $res->getCollection()->transform(function ($row) use ($keywordMaps, $pageMaps, $differInDay, &$keyMaps, $metaMaps) {
            // Your code here
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
            if (isset($keywordMaps[$row->keyword_id])) {
                $row->keyword = $keywordMaps[$row->keyword_id];
            }
            $row->avg_ctr = round($row->avg_ctr, 2);
            $row->avg_positions = round($row->sum_positions / $differInDay, 2);
            $keyMaps[$row->page_id . '_' . $row->keyword_id] = (array)$row;
            return (array)$row;
        });

        return [$keyMaps, $res->toArray()];
    }


}

