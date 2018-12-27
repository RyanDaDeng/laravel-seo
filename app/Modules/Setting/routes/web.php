<?php

Route::prefix('setting/web')->middleware(["web","auth"])->namespace('App\Modules\Setting\Controllers\Web')->group(function () {
    Route::get('/all-settings', 'SettingWebController@getAllSettings')->name('Setting.getAllSettings');
});

