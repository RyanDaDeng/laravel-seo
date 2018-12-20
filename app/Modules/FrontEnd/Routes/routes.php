<?php
/**
 * Created by PhpStorm.
 * User: rayndeng
 * Date: 12/12/18
 * Time: 3:38 PM
 */


Route::prefix('app')->middleware(['web', 'auth'])->namespace('App\Modules\FrontEnd\Controllers')->group(function () {
    Route::get('', 'FrontEndController@index')->name('FrontEnd.index');
    Route::middleware('admin')->get('api-management', 'FrontEndController@apiManagement')->name('FrontEnd.apiManagement');
});
