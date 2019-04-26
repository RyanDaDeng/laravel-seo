<?php

namespace App\Modules\Keywords\Services;

use App\Modules\DataMigration\Services\SnapshotSummaryService;
use App\Modules\JobHistory\Services\JobHistoryService;
use App\Modules\Keywords\Models\Keyword;
use App\Modules\Keywords\Models\Page;
use App\Modules\Keywords\Models\QueryDetails;
use App\Modules\SeoAgent\Models\SeoAgentCurrentData;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;

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


    public static function getFilteredKeywords(Builder $query, $exactly = null, $excludes = null, $contains = null)
    {

        $query = $query->join('tbl_gw_keywords as keywords', 'keywords.id', 'summary.keyword');
        if ($exactly) {
            $query = $query->where('keywords.keyword', '=', $exactly);

        } else {

            if ($excludes) {
                foreach ($excludes as $exclude) {
                    $query = $query->where('keywords.keyword', 'not like', "%$exclude%");
                }

            }

            if ($contains) {
                foreach ($contains as $contain) {
                    $query = $query->where('keywords.keyword', 'like', "%$contain%");
                }
            }
        }

        return $query;
    }


    public static function getPageIdBetweenDate(Carbon $dateFrom, Carbon $dateTo)
    {

        $pageIds = QueryDetails::query()->selectRaw('distinct page')
            ->whereBetween('date', [
                    $dateFrom->format('Y-m-d'),
                    $dateTo->format('Y-m-d')
                ]
            )
            ->get()
            ->pluck('page');

        return $pageIds;
    }

    public static function summarize(Carbon $dateFrom, Carbon $dateTo)
    {

        $dataCollect = QueryDetails::query()
            ->selectRaw(
                '
                tbl_gw_query_details.page as page_id,
                tbl_gw_query_details.keyword as keyword_id,
                tbl_gw_query_details.device as device_type,
	sum(tbl_gw_query_details.clicks) as sum_clicks,
	round(sum(tbl_gw_query_details.impressions),2) as sum_impressions, 
	round(sum(tbl_gw_query_details.position),2) as sum_positions,
	round(sum(tbl_gw_query_details.impressions*tbl_gw_query_details.position),2) as sum_average_weight_ranking,
    round(sum(tbl_gw_query_details.clicks)/sum(tbl_gw_query_details.impressions),4) as avg_ctr')
            ->whereBetween('date',
                [
                    $dateFrom->format('Y-m-d'),
                    $dateTo->format('Y-m-d')
                ])
            ->groupBy(['tbl_gw_query_details.page', 'tbl_gw_query_details.keyword', 'tbl_gw_query_details.device'])
            ->get();
        return $dataCollect;
    }


    public static function summarizeByItem(Carbon $dateFrom, Carbon $dateTo, $page, $keyword, $device)
    {

        $dataCollect = QueryDetails::query()
            ->selectRaw(
                '
                tbl_gw_query_details.page as page_id,
                tbl_gw_query_details.keyword as keyword_id,
                tbl_gw_query_details.device as device_type,
	sum(tbl_gw_query_details.clicks) as sum_clicks,
	round(sum(tbl_gw_query_details.impressions),2) as sum_impressions, 
	round(sum(tbl_gw_query_details.position),2) as sum_positions,
	round(sum(tbl_gw_query_details.impressions*tbl_gw_query_details.position),2) as sum_average_weight_ranking,
    round(sum(tbl_gw_query_details.clicks)/sum(tbl_gw_query_details.impressions),4) as avg_ctr')
            ->whereBetween('date',
                [
                    $dateFrom->format('Y-m-d'),
                    $dateTo->format('Y-m-d')
                ])
            ->where('page', $page)
            ->where('keyword', $keyword)
            ->where('device', $device)
            ->groupBy(['tbl_gw_query_details.page', 'tbl_gw_query_details.keyword', 'tbl_gw_query_details.device'])
            ->get();
        return $dataCollect;
    }

    /**
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @return \Illuminate\Database\Query\Builder
     */
    public static function getCompareToRangeBaseQuery(Carbon $dateFrom, Carbon $dateTo)
    {
        $unionQuery = self::customRangeUnionQuery($dateFrom, $dateTo);
        $baseQuery = \DB::table(\DB::raw("({$unionQuery->toSql()}) as summary"))->selectRaw(
            '
                summary.page as page_id,
                summary.keyword as keyword_id,
	sum(summary.sum_clicks) as sum_clicks,
	round(sum(summary.sum_impressions),2) as sum_impressions, 
	round(sum(summary.sum_positions),2) as sum_positions,
	round(sum(summary.sum_average_weight_ranking),2) as sum_average_weight_ranking,
    round(sum(summary.sum_clicks)/sum(summary.sum_impressions),4) as avg_ctr'
        )
            ->groupBy(['summary.page', 'summary.keyword']);
        return $baseQuery;
    }


    /**
     * @param Carbon $dateFrom
     * @param Carbon $dateTo
     * @return \Illuminate\Database\Query\Builder
     */
    public static function getCustomRangeBaseQuery(Carbon $dateFrom, Carbon $dateTo)
    {


        $unionQuery = self::customRangeUnionQuery($dateFrom, $dateTo);
        $baseQuery = \DB::table(\DB::raw("({$unionQuery->toSql()}) as summary"))->selectRaw(
            '
                summary.page as page_id,
                summary.keyword as keyword_id,
                profile.is_primary as is_primary,
                profile.click_potential as click_potential,
                profile.ctr_benchmark as ctr_benchmark,
	sum(summary.sum_clicks) as sum_clicks,
	round(sum(summary.sum_impressions),2) as sum_impressions, 
	round(sum(summary.sum_positions),2) as sum_positions,
	round(sum(summary.sum_average_weight_ranking),2) as sum_average_weight_ranking,
    round(sum(summary.sum_clicks)/sum(summary.sum_impressions),4) as avg_ctr,
    round((sum(summary.sum_clicks)/sum(summary.sum_impressions))*100-profile.ctr_benchmark,4) as ctr_difference'
        )
            ->join('tbl_gw_query_profiles as profile', function ($join) {
                $join->on('summary.page', '=', 'profile.page');
                $join->on('summary.keyword', '=', 'profile.keyword');

            })
            ->groupBy(['summary.page', 'summary.keyword']);
        return $baseQuery;
    }


    public static function customRangeUnionQuery(Carbon $dateFrom, Carbon $dateTo)
    {
        $dateRange = JobHistoryService::getDateRangeArray($dateFrom, $dateTo);

        $tableName = SnapshotSummaryService::getTableName($dateRange[0]['start'], $dateRange[0]['end']);
        $query = \DB::table("$tableName");
        array_shift($dateRange);
        foreach ($dateRange as $range) {
            $tableName = SnapshotSummaryService::getTableName($range['start'], $range['end']);
            $query->unionAll(\DB::table("$tableName"));
        }
        return $query;
    }


    public static function getCompareToData(Carbon $aDateFrom, Carbon $aDateTo, $pageId, $keywordId, $deviceType)
    {

        $query = self::getCompareToRangeBaseQuery($aDateFrom, $aDateTo)
            ->where('summary.page', '=', $pageId)
            ->where('summary.keyword', '=', $keywordId);

        // device filter
        if (!empty($deviceType)) {
            $query = $query->where('summary.device', '=', QueryDetails::getDeviceTypeByName($deviceType));
        }
        return $query->first();


    }

    public static function getKeywordList($aDateFrom,
                                          $aDateTo,
                                          $device,
                                          $url,
                                          $urlFilter,
                                          $sortBy,
                                          $sortOrder,
                                          $perPage,
                                          $pathMd5,
                                          $isPrimary,
                                          $exactlyKeyword,
                                          $containKeywords,
                                          $excludeKeywords)
    {


        $aDateFrom = Carbon::parse($aDateFrom);
        $aDateTo = Carbon::parse($aDateTo);

        $query = self::getCustomRangeBaseQuery($aDateFrom, $aDateTo);


        $page = null;
        if ($pathMd5) {
            $page = Page::query()->where('path_md5', $pathMd5)->first();
        }

        // is primary filter
        if (!empty($isPrimary) && $isPrimary == '1') {
            $query = $query->where('is_primary', '=', 1);
        }

        // device filter
        if (!empty($device)) {
            $query = $query->where('summary.device', '=', QueryDetails::getDeviceTypeByName($device));
        }

        // md5 filter
        if ($page) {
            $query = $query->where('summary.page', '=', $page->id);
        }
        // url filter
        $urlData = [];
        if (!empty($url) && !empty($urlFilter)) {
            $urlData = self::getFilteredPages($urlFilter, $url);
            $query = $query->whereIn('summary.page', $urlData->pluck('id'));
        }


        // keyword filter
        $keywordData = [];
        if (!empty($excludeKeywords) || !empty($containKeywords) || !empty($exactlyKeyword)) {
            $query = self::getFilteredKeywords($query, $exactlyKeyword, $excludeKeywords, $containKeywords);
        }


        if (in_array($sortBy, ['ctr_difference', 'click_potential', 'ctr_benchmark'])) {
            $sortOrder = $sortOrder === 'asc' ? 'asc' : 'desc';
            $query = $query->orderBy($sortBy, $sortOrder);
        }


        $res = $query->paginate($perPage)->toArray();

        $dataCollect = collect($res['data']);
        $keywordIds = $dataCollect->pluck('keyword_id')->unique();
        $pageIds = $dataCollect->pluck('page_id')->unique();
        $keywordMaps = !empty($keywordData) ? $keywordData->pluck('keyword', 'id') : Keyword::query()->whereIn('id', $keywordIds)->pluck('keyword', 'id');
        $pages = !empty($urlData) ? $urlData : Page::query()->whereIn('id', $pageIds)->get();
        $metaMaps = SeoAgentCurrentData::query()->whereIn('hash', $pages->pluck('path_md5'))->get()->keyBy('hash');
        $pageMaps = $pages->keyBy('id')->toArray();

        $data = [];
        foreach ($dataCollect as $row) {

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

            $data[] = $row;
        }

        $res['data'] = $data;

        return $res;
    }
}

