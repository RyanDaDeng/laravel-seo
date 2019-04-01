<?php


// for api
// for api
Route::middleware(['auth:api', 'admin'])->prefix('api/keywords/v1')->group(function () {
    Route::patch('pages', 'App\Modules\Keywords\Controllers\Api\V1\KeywordApiController@syncPages')->name('SeoAgent.api.syncPages');
    Route::patch('query-details', 'App\Modules\Keywords\Controllers\Api\V1\KeywordApiController@syncQueryDetails')->name('SeoAgent.api.syncQueryDetails');
    Route::patch('keywords', 'App\Modules\Keywords\Controllers\Api\V1\KeywordApiController@syncKeywords')->name('SeoAgent.api.syncKeywords');
});