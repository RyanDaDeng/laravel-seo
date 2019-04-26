<?php

namespace App\Modules\Keywords\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\JobHistory\Services\JobHistoryService;
use App\Modules\Keywords\Models\Page;
use App\Modules\Keywords\Models\QueryDetails;
use App\Modules\Keywords\Services\QueryProfileService;
use App\Modules\Keywords\Services\KeywordService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class KeywordWebController extends Controller
{



    public function getFilterOperand($filter, $url)
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

    public function getSearchKeyword(Request $request)
    {
        $pathMd5 = $request->query('path_md5');

        $rules = [
            'a_date_from' => 'required|date|date_format:Y-m-d',
            'a_date_to' => 'required|date|date_format:Y-m-d',
            'b_date_from' => 'nullable|date|date_format:Y-m-d',
            'b_date_to' => 'nullable|date|date_format:Y-m-d'
        ];

        $v = Validator::make($request->all(), $rules);
        if ($v->fails()) {
            return \Response::json([
                'success' => false,
                'message' => $v->errors()->first()
            ], 400);
        }


        $aDateFromCarbon = Carbon::parse($request->query('a_date_from'));
        $aDateToCarbon = Carbon::parse($request->query('a_date_to'));
        $aDateFrom = Carbon::parse($request->query('a_date_from'))->format('Y-m-d');
        $aDateTo = Carbon::parse($request->query('a_date_to'))->format('Y-m-d');

        $device = $request->query('device');
        $url = $request->query('url');
        $urlFilter = $request->query('url_filter');

        $exactlyKeyword = $request->query('exactly_keyword');
        $containKeyword = $request->query('contain_keywords');
        $excludeKeywords = $request->query('exclude_keywords');

        $sortBy = $request->query('sort_by');
        $request->query('sort_order');
        $perPage = $request->query('per_page');
        $sortOrder = $request->query('sort_order');
        $isPrimary = $request->query('is_primary');

        // check if date range exists


        // if job not finished
        if (!JobHistoryService::createAndValidateCustomDateRange($aDateFromCarbon, $aDateToCarbon)) {
            return \Response::json(['warning' => true, 'message' => 'There is a job has been running for Compare From range. Please wait it to be finished.'], 400);
        }

        $aRangeData = KeywordService::getKeywordList(
            $aDateFrom,
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
            $containKeyword,
            $excludeKeywords);

        $hasCompareTo = false;
        $bDateFromCarbon = null;
        $bDateToCarbon = null;

        // if compare to date range is provided, then we get data for the period
        if ($request->query('b_date_from') && $request->query('b_date_to')) {
            $bDateFromCarbon = Carbon::parse($request->query('b_date_from'));
            $bDateToCarbon = Carbon::parse($request->query('b_date_to'));
            if (!JobHistoryService::createAndValidateCustomDateRange($bDateFromCarbon, $bDateToCarbon)) {
                return \Response::json(['warning' => true, 'message' => 'There is a job has been running for Compare To range. Please wait it to be finished.'], 400);
            }
            $hasCompareTo = true;
        }

        // reformat data and adding compare data
        foreach ($aRangeData['data'] as $key => $row) {
            // if the page exists
            if (isset($row->page)) {
                // add additional data and reformat
                $aRangeData['data'][$key]->path = parse_url($row->page)['path'];
                $aRangeData['data'][$key]->avg_ctr = round($row->avg_ctr, 4);
                $aRangeData['data'][$key]->avg_positions = round($row->sum_average_weight_ranking / $row->sum_impressions, 4);
                $aRangeData['data'][$key]->trend = [];
                // has compare to data, then appending compare result
                if ($hasCompareTo) {
                    $compareTo = KeywordService::getCompareToData($bDateFromCarbon, $bDateToCarbon, $row->page_id, $row->keyword_id, $device);
                    if($compareTo){
                        $compareTo->avg_positions = round($compareTo->sum_average_weight_ranking / $compareTo->sum_impressions, 4);
                        $aRangeData['data'][$key]->trend = [
                            'positions_trend' => round($aRangeData['data'][$key]->avg_positions - $compareTo->avg_positions, 2),
                            'ctr_trend' => $this->compare($aRangeData['data'][$key]->avg_ctr, $compareTo->avg_ctr)
                        ];
                        $aRangeData['data'][$key]->compareTo = $compareTo;
                    }
                }
            }
        }
        return $aRangeData;
    }

    public function compare($new, $old)
    {
        if ($old == 0) {
            return '∞';
        }
        return round((($new - $old) / $old) * 100, 2);

    }


    public function updateClickPotential(Request $request, $pageId, $keywordId)
    {
        $obj = QueryProfileService::getById($pageId, $keywordId);
        $obj->click_potential = $request->input('click');
        $obj->save();

        return $obj;
    }


    public function updateCtrBenchmark(Request $request, $pageId, $keywordId)
    {
        $obj = QueryProfileService::getById($pageId, $keywordId);
        $obj->ctr_benchmark = floatval($request->input('benchmark'));
        $obj->save();

        return $obj;
    }

    public function setPrimary(Request $request, $pageId, $keywordId)
    {
        $obj = QueryProfileService::getById($pageId, $keywordId);
        $isPrimary = $request->input('is_primary');
        if ($obj) {
            $obj->is_primary = $isPrimary;
            $obj->save();
        }
        return $obj;
    }


    public function checkIfMd5Exists(Request $request, $md5)
    {
        $obj = Page::query()->where('path_md5', $md5)->first();

        if ($obj) {
            return ['success' => true, 'data' => $obj];
        } else {
            return ['success' => false];
        }

    }


}

