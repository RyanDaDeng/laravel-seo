<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


/**
 * SeoAgent
 */
Route::prefix('web/seo-agent')->group(function () {

    Route::put('seo-metas/{id}', 'Web\SeoAgentController@updateMetaData');

    Route::get('seo-metas/{id}', 'Web\SeoAgentController@getMetaDatById');

    Route::get('seo-metas', 'Web\SeoAgentController@getMetaData');
});