<?php

Route::middleware(['web'])->prefix('seoagent/web')->namespace('App\Modules\SeoAgent\Controllers\Web')->group(function () {
    Route::delete('draft-data/{id}', 'SeoAgentWebController@deleteDraftData')->name('SeoAgent.deleteDraftData');
    Route::put('draft-data/{id}', 'SeoAgentWebController@updateDraftData')->name('SeoAgent.updateDraftData');
    Route::get('draft-data', 'SeoAgentWebController@getDraftData')->name('SeoAgent.getDraftData');

    Route::get('draft-data/{hash}/histories', 'SeoAgentWebController@getHistoryByMetaHash')->name('SeoAgent.getHistoryByMetaHash');
});
