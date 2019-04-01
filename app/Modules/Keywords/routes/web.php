<?php

Route::prefix('keywords/web')->namespace('App\Modules\Keywords\Controllers\Web')->group(function () {
    Route::get('pages/{pageId}/keywords', 'KeywordWebController@getSummaryPage')->name('getSummaryPage');
    Route::get('keywords', 'KeywordWebController@getSearchKeyword')->name('getSearchKeyword');

    Route::put('keywords/{id}/click', 'KeywordWebController@updateClickPotential')->name('updateClickPotential');
    Route::put('keywords/{id}/benchmark', 'KeywordWebController@updateCtrBenchmark')->name('updateCtrBenchmark');
    Route::put('keywords/{id}/primary', 'KeywordWebController@setPrimary')->name('setPrimary');
    Route::get('keywords/{md5}/md5', 'KeywordWebController@checkIfMd5Exists')->name('checkIfMd5Exists');
});
