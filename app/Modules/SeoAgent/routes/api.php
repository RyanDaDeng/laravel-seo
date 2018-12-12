<?php


// for api
Route::prefix('seoagent/api/v1')->group(function () {
    Route::get('/draft-data', 'App\Modules\SeoAgent\Controllers\Api\V1\SeoAgentApiController@getDraftData')->name('SeoAgent.getDraftData');
    Route::put('/draft-data/{id}', 'App\Modules\SeoAgent\Controllers\Api\V1\SeoAgentApiController@updateDraftData')->name('SeoAgent.updateDraftData');
    Route::get('/change-requests', 'App\Modules\SeoAgent\Controllers\Api\V1\SeoAgentApiController@getChangeRequests')->name('SeoAgent.getChangeRequests');
    Route::put('/change-requests/{hash_id}', 'App\Modules\SeoAgent\Controllers\Api\V1\SeoAgentApiController@updateChangeRequests')->name('SeoAgent.updateChangeRequests');
    Route::post('/change-requests', 'App\Modules\SeoAgent\Controllers\Api\V1\SeoAgentApiController@createChangeRequests')->name('SeoAgent.createChangeRequests');
    Route::patch('/batch/change-requests', 'App\Modules\SeoAgent\Controllers\Api\V1\SeoAgentApiController@bulkUpdateOrInsertChangeRequests')->name('SeoAgent.bulkUpdateOrInsertChangeRequests');
});