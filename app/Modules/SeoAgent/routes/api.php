<?php


// for api
Route::middleware('auth:api')->prefix('api/seoagent/v1')->group(function () {
    Route::post('/current-data', 'App\Modules\SeoAgent\Controllers\Api\V1\SeoAgentApiController@createCurrentData')->name('SeoAgent.api.createCurrentData');
    Route::put('/current-data/{hash}', 'App\Modules\SeoAgent\Controllers\Api\V1\SeoAgentApiController@updateCurrentDataByHash')->name('SeoAgent.api.updateCurrentDataByHash');
    Route::get('/draft-data', 'App\Modules\SeoAgent\Controllers\Api\V1\SeoAgentApiController@getOnlyDraftData')->name('SeoAgent.api.getOnlyDraftData');
    Route::get('/current-data/{hash}', 'App\Modules\SeoAgent\Controllers\Api\V1\SeoAgentApiController@getCurrentDataByHash')->name('SeoAgent.api.getCurrentDataByHash');

    Route::patch('/current-data', 'App\Modules\SeoAgent\Controllers\Api\V1\SeoAgentApiController@patchCurrentData')->name('SeoAgent.api.patchCurrentData');
});

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});