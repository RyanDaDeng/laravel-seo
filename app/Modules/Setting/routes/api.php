<?php


Route::middleware(['auth:api', 'admin'])->prefix('api/setting/v1')->namespace('App\Modules\Setting\Controllers\Api\V1')->group(function () {
    Route::get('/push-settings', 'SettingApiController@getPushSettings')->name('Setting.getPushSettings');
    Route::get('/pull-settings', 'SettingApiController@getPullSettings')->name('Setting.getPullSettings');
    Route::put('/push-settings', 'SettingApiController@updatePushSettings')->name('Setting.updatePushSettings');
    Route::put('/pull-settings', 'SettingApiController@updatePullSettings')->name('Setting.updatePullSettings');
    Route::get('/all-settings', 'SettingApiController@getAllSettings')->name('Setting.getAllSettings');
    Route::get('/google-settings', 'SettingApiController@getGoogleSetting')->name('Setting.getGoogleSetting');
    Route::put('/google-settings', 'SettingApiController@updateGoogleSettings')->name('Setting.updateGoogleSettings');
});
