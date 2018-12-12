<?php

Route::prefix('seoagent/web')->middleware(['web', 'auth'])->namespace('App\Modules\SeoAgent\Controllers\Web')->group(function () {
    Route::get('draft-data', 'SeoAgentWebController@getDraftData')->name('SeoAgent.getDraftData');
});
