<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/pusher', function () {


//    $data = \Illuminate\Support\Facades\DB::
//
//
//
//    dd(2);
//    //
    $ditincts = \App\Modules\Keywords\Models\QueryDetails::query()
        ->select('page', 'keyword', 'device as device_type')
        ->where('date', '>=', '2019-02-01')
        ->where('date', '<=', '2019-02-28')
        ->groupBy('page', 'keyword', 'device')->get();
dd($ditincts);
    $dateFrom = \Carbon\Carbon::parse('2019-02-01');
    $dateTo = \Carbon\Carbon::parse('2019-02-28');

    $instance = \App\Modules\DataMigration\Services\SnapshotSummaryService::instance($dateFrom, $dateTo);
    foreach ($ditincts as $ditinct) {
        $res = \App\Modules\Keywords\Services\KeywordService::summarizeByItem(
            $dateFrom,
            $dateTo,
            $ditinct->page, $ditinct->keyword, $ditinct->device_type);
        $instance->insertData($res);
    }

    dd(2);

    $dateRangeFrom = \Carbon\Carbon::parse('2019-01-01');
    $dateRangeTo = \Carbon\Carbon::parse('2019-03-31');


    $startOfFromMonth = $dateRangeFrom->startOfMonth();
    $startOfToMonth = $dateRangeTo->startOfMonth();

    while ($startOfFromMonth <= $startOfToMonth) {

        $instance = new \App\Modules\DataMigration\Services\SnapshotSummaryService(
            $startOfFromMonth,
            $startOfFromMonth->copy()->endOfMonth()
        );


        if (!$instance->checkTableExists()) {
            $instance->createTable();
        }

        $startOfFromMonth->addMonth();

    }

    // get last day of month
//    $lastDay = '2018-09'


    dd($startOfFromMonth);
    $gap = 2;
    $period = \Carbon\CarbonInterval::days($gap)->toPeriod($dateRangeFrom->format('Y-m-d'), $dateRangeTo->format('Y-m-d'));
    $dates = collect();
    if ($dateRangeFrom->diffInDays($dateRangeTo) < $gap) {
        $dates->push($dateRangeFrom);
        $dates->push($dateRangeTo);
    } else {
        foreach ($period as $key => $date) {
            $dates->push($date);
        }
    }

    foreach ($dates->values()->chunk(2)->toArray() as $dateRange) {

        if (count($dateRange) === 2) {
            $dateFrom = reset($dateRange);
            $dateTo = last($dateRange);
        } else {
            $dateFrom = reset($dateRange);
            $dateTo = reset($dateRange);
        }


    }

    dd(2);

    $dateFrom = \Carbon\Carbon::parse('2019-01-01');
    $dateTo = \Carbon\Carbon::parse('2019-01-05');
    $differInDays = $dateTo->diffInDays($dateFrom);


    $gap = 1;

    $dateRangeFrom = $dateFrom;
    $dateRangeTo = $dateRangeFrom;
    $isOk = true;
    // [dateFrom,dateTo)
    while ($isOk && $dateTo >= $dateRangeTo) {

        if ($isOk && $dateRangeTo->copy()->addDays($gap) > $dateTo) {
            $dateRangeTo = $dateTo;
            $isOk = false;
        }


        $dateRangeTo->addDays($gap);

    }

    dd($differInDays);
//    $dataCollect = QueryDetails::query()
//        ->selectRaw(
//            '
//                tbl_gw_query_details.page as page_id,
//                tbl_gw_query_details.keyword as keyword_id,
//                tbl_gw_query_details.device as device_type,
//	sum(tbl_gw_query_details.clicks) as sum_clicks,
//	round(sum(tbl_gw_query_details.impressions),2) as sum_impressions,
//	round(sum(tbl_gw_query_details.position),2) as sum_positions,
//	round(sum(tbl_gw_query_details.impressions*tbl_gw_query_details.position),2) as sum_average_weight_ranking,
//    round(sum(tbl_gw_query_details.clicks)/sum(tbl_gw_query_details.impressions),4) as avg_ctr')
//        ->whereBetween('date',
//            [
//                $dateFrom->format('Y-m-d'),
//                $dateTo->format('Y-m-d')
//            ])
//        ->groupBy(['tbl_gw_query_details.page', 'tbl_gw_query_details.keyword','tbl_gw_query_details.device'])
//        ->get();
// dd(3);

});
Route::get('/', function () {
    return redirect('app');
});

Route::get('/home', function () {
    return redirect('app');
});

Route::middleware('admin')->get('app/api-management', function () {
    return view('modules.api-management.index');
});


Auth::routes();


