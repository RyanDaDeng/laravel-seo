<?php

Route::prefix('job-history/web')->namespace('App\Modules\JobHistory\Controllers\Web')->group(function () {

    Route::get('job-histories', 'JobHistoryWebController@getJobHistories')->name('getJobHistories');
    Route::put('job-histories/{id}/rerun', 'JobHistoryWebController@rerunJobById')->name('rerunJobById');
    Route::delete('job-histories/{id}', 'JobHistoryWebController@deleteJobById')->name('deleteJobById');
});
