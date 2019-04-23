<?php

namespace App\Modules\Keywords\Services;

use App\Modules\DataMigration\Services\SnapshotSummaryService;
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


    public static function calculateTodayData(Carbon $dateFrom, Carbon $dateTo, $pageIds)
    {

        $dataCollect = QueryDetails::query()
            ->selectRaw(
                '
                tbl_gw_query_details.page as page_id,
                tbl_gw_query_details.keyword as keyword_id,
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
            ->groupBy(
                [
                    'tbl_gw_query_details.page',
                    'tbl_gw_query_details.keyword',
                    'tbl_gw_query_details.date',
                    'tbl_gw_query_details.device'
                ]
            )
            ->get();
        return $dataCollect;
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
    public static function getMonthlyBaseQuery(Carbon $dateFrom, Carbon $dateTo)
    {

        $unionQuery = self::monthlyUnionQuery($dateFrom, $dateTo);
        $baseQuery = \DB::table(\DB::raw("({$unionQuery->toSql()}) as summary"))->selectRaw(
            '
                summary.page as page_id,
                summary.keyword as keyword_id,
                summary.device as device_type,
	sum(summary.sum_clicks) as sum_clicks,
	round(sum(summary.sum_impressions),2) as sum_impressions, 
	round(sum(summary.sum_positions),2) as sum_positions,
	round(sum(summary.sum_average_weight_ranking),2) as sum_average_weight_ranking,
    round(sum(summary.sum_clicks)/sum(summary.sum_impressions),4) as avg_ctr'
        )
            ->groupBy(['summary.page', 'summary.keyword', 'summary.device']);

        return $baseQuery;
    }

    public static function monthlyUnionQuery(Carbon $dateFrom, Carbon $dateTo)
    {

        $startDateCarbon = $dateFrom->copy()->startOfMonth();
        $endDateCarbon = $dateTo->copy()->endOfMonth();

        // create initial first table sub query
        $tableName = SnapshotSummaryService::getTableNameByMonthlyRange($startDateCarbon);
        $query = \DB::table("$tableName");

        // loop and generate sub-query
        while ($endDateCarbon->greaterThan($startDateCarbon->addMonth())) {
            $tableName = SnapshotSummaryService::getTableNameByMonthlyRange($startDateCarbon);
            $query->unionAll(\DB::table("$tableName"));
        }

        return $query;
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


        $aDateFrom = Carbon::parse($aDateFrom);
        $aDateTo = Carbon::parse($aDateTo);

        $query = self::getMonthlyBaseQuery($aDateFrom, $aDateTo);
        $page = null;
        if ($pathMd5) {
            $page = Page::query()->where('path_md5', $pathMd5)->first();
        }

        $query = $query->whereBetween('summary.to_date', [$aDateFrom->format('Y-m-d'), $aDateTo->format('Y-m-d')]);

        // is primary filter
        if (!empty($isPrimary) && $isPrimary == '1') {
            $query = $query
                ->join('tbl_gw_query_profiles', function ($join) {
                    $join->on('summary.page', '=', 'tbl_gw_query_profiles.page');
                    $join->on('summary.keyword', '=', 'tbl_gw_query_profiles.keyword');

                })
                ->where('tbl_gw_query_profiles.is_primary', '=', 1);
        }

        // device filter
        if (!empty($device)) {
            $query = $query->where('device', '=', QueryDetails::getDeviceTypeByName($device));
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
        if (!empty($keyword) && !empty($keywordFilter)) {
            $keywordData = self::getFilteredKeywords($keywordFilter, $keyword);
            $query = $query->whereIn('summary.keyword', $keywordData->pluck('id'));
        }

        // sort filter
//        if ($sortBy) {
//            $sortOrder = $sortOrder === 'asc' ? 'asc' : 'desc';
//            $query = $query->orderBy($sortBy, $sortOrder);
//        }


        if ($sortBy === 'click_potential') {
            $sortOrder = $sortOrder === 'asc' ? 'asc' : 'desc';
            $query = $query
                ->join('tbl_gw_query_profiles', function ($join) {
                    $join->on('summary.page', '=', 'tbl_gw_query_profiles.page');
                    $join->on('summary.keyword', '=', 'tbl_gw_query_profiles.keyword');

                })
                ->orderBy($sortBy, $sortOrder);
        }

        if ($sortBy === 'ctr_benchmark') {
            $sortOrder = $sortOrder === 'asc' ? 'asc' : 'desc';
            $query = $query
                ->join('tbl_gw_query_profiles', function ($join) {
                    $join->on('summary.page', '=', 'tbl_gw_query_profiles.page');
                    $join->on('summary.keyword', '=', 'tbl_gw_query_profiles.keyword');

                })
                ->orderBy($sortBy, $sortOrder);
        }


        $res = $query->paginate($perPage)->toArray();

        $dataCollect = collect($res['data']);
        $keywordIds = $dataCollect->pluck('keyword_id')->unique();
        $pageIds = $dataCollect->pluck('page_id')->unique();
        $keywordMaps = !empty($keywordData) ? $keywordData->pluck('keyword', 'id') : Keyword::query()->whereIn('id', $keywordIds)->pluck('keyword', 'id');
        $pages = !empty($urlData) ? $urlData : Page::query()->whereIn('id', $pageIds)->get();
        $metaMaps = SeoAgentCurrentData::query()->whereIn('hash', $pages->pluck('path_md5'))->get()->keyBy('hash');
        $pageMaps = $pages->keyBy('id')->toArray();
        $keyMaps = [];

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

            // re-format the data number and calculate extra average
            $row->avg_ctr = round($row->avg_ctr, 4);
            $row->avg_positions = round($row->sum_average_weight_ranking / $row->sum_impressions, 4);

            $row->device_name = QueryDetails::getDeviceNameById($row->device_type);
            // create profile map
            $row->profile = QueryProfile::query()
                ->where('page', $row->page_id)
                ->select('is_primary', 'ctr_benchmark', 'click_potential', 'id')
                ->where('keyword', $row->keyword_id)
                ->first();
            if (isset($keywordMaps[$row->keyword_id]) && isset($pageMaps[$row->page_id])) {
                $row->index_id = $row->page_id . '_' . $row->keyword_id;
                // put hash mapping into maps for the reference of page+keyword
                $keyMaps[$row->index_id] = (array)$row;
            } else {
                $row->index_id = null;
            }
            $data[] = $row;
        }

        $res['data'] = $data;

        return [$keyMaps, $res];
    }


}

