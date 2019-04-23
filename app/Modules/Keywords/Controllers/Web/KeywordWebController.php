<?php

namespace App\Modules\Keywords\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Modules\JobHistory\Services\JobHistoryQuery;
use App\Modules\Keywords\Models\Page;
use App\Modules\Keywords\Models\QueryDetails;
use App\Modules\Keywords\Models\QueryProfile;
use App\Modules\Keywords\Services\QueryProfileQueries;
use App\Modules\SeoAgent\Models\SeoAgentCurrentData;
use App\Modules\Keywords\Services\KeywordQueries;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class KeywordWebController extends Controller
{

    protected $domain = 'https://www.inkstation.com.au';

    public function getSummaryPage(Request $request, $id)
    {

        $seoMeta = SeoAgentCurrentData::query()->where('id', $id)->first();
        if (!$seoMeta) {
            return \Response::json(['success' => false,
                'data' => 'not found id'], 400);
        }

        $path = substr($seoMeta->path, 0) === '/' ? substr($seoMeta->path, 1) : $seoMeta->path;
        $fullpath = $this->domain . '/' . $path;
        $md5 = md5($fullpath);

        $page = Page::query()->where('md5', $md5)->first();

        if (!$page) {
            return \Response::json(['success' => false, 'data' => 'not found page'], 400);
        }

        $keywords = QueryDetails::query()->with('keyword');

        if ($request->device) {
            $keywords = $keywords->where('device', $request->device);
        }
        $keywords = $keywords->where('page', $page->id)->get()->toArray();


        return \Response::json(['success' => true, 'data' => $keywords], 200);
        return $keywords;
        dd($keywords);


        dd(md5('https://www.inkstation.com.au/Deal'));
    }


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
        $keyword = $request->query('keyword');
        $keywordFilter = $request->query('keyword_filter');
        $sortBy = $request->query('sort_by');
        $request->query('sort_order');
        $perPage = $request->query('per_page');
        $sortOrder = $request->query('sort_order');
        $isPrimary = $request->query('is_primary');

        // check if date range exists


        // if job not finished
        if (!JobHistoryQuery::createAndValidateDateRangeOnMonthly($aDateFromCarbon, $aDateToCarbon)) {
            return \Response::json(['warning' => true, 'message' => 'There is a job has been running for Compare From range. Please wait it to be finished.'], 400);
        }

        list($aRangeKeys, $aRangeData) = KeywordQueries::getKeywordList(
            $aDateFrom,
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
            $isPrimary);

        list($bRangeKeys, $bRangeData) = [null, null];

        // if compare to date range is provided, then we get data for the period
        if ($request->query('b_date_from') && $request->query('b_date_to')) {

            $bDateFromCarbon = Carbon::parse($request->query('b_date_from'));
            $bDateToCarbon = Carbon::parse($request->query('b_date_to'));

            $bDateFrom = Carbon::parse($request->query('b_date_from'))->format('Y-m-d');
            $bDateTo = Carbon::parse($request->query('b_date_to'))->format('Y-m-d');

            if (!JobHistoryQuery::createAndValidateDateRangeOnMonthly($bDateFromCarbon, $bDateToCarbon)) {
                return \Response::json(['warning' => true, 'message' => 'There is a job has been running for Compare To range. Please wait it to be finished.'], 400);
            }

            list($bRangeKeys, $bRangeData) = KeywordQueries::getKeywordList(
                $bDateFrom,
                $bDateTo,
                $device,
                $url,
                $urlFilter,
                $keyword,
                $keywordFilter,
                $sortBy,
                $sortOrder,
                $perPage,
                $pathMd5,
                $isPrimary);
        }

        foreach ($aRangeData['data'] as $key => $row) {
            $index = $row->index_id;
            if (isset($row->page)) {
                $aRangeData['data'][$key]->path = parse_url($row->page)['path'];
                if ($bRangeKeys && isset($bRangeKeys[$index])) {
                    $aRangeData['data'][$key]->compare = $bRangeKeys[$index];
                    $aRangeData['data'][$key]->trend = [
                        'positions_trend' => round($aRangeData['data'][$key]->avg_positions - $bRangeKeys[$index]->avg_positions, 2),
                        'ctr_trend' => $this->compare($aRangeData['data'][$key]->avg_ctr, $bRangeKeys[$index]->avg_ctr)
                    ];
                } else {
                    $aRangeData['data'][$key]->compare = [];
                    $aRangeData['data'][$key]->trend = [];
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
        $obj = QueryProfileQueries::getById($pageId, $keywordId);
        $obj->click_potential = $request->input('click');
        $obj->save();

        return $obj;
    }


    public function updateCtrBenchmark(Request $request, $pageId, $keywordId)
    {
        $obj = QueryProfileQueries::getById($pageId, $keywordId);
        $obj->ctr_benchmark = floatval($request->input('benchmark'));
        $obj->save();

        return $obj;
    }

    public function setPrimary(Request $request, $pageId, $keywordId)
    {
        $obj = QueryProfileQueries::getById($pageId, $keywordId);
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

