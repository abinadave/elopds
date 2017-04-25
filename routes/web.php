<?php

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

Route::get('/', 'HomeController@index');

Auth::routes();
Route::group(['middleware' => 'auth'], function () {
    Route::post('officials_fetch', 'OfficialController@fetch');
    Route::get('province', 'ProvinceController@fetchUnique');
    Route::get('citymun', 'ProvinceController@fetchCityMuns');
    Route::post('search_query_province', 'OfficialController@searchQueryProvince');
    Route::post('search_query_citymun', 'OfficialController@searchQueryCItyMun');
    Route::post('get_relared_mun', 'ProvinceController@fetchRelatedCityMun');
    Route::post('search_query_all', 'OfficialController@searchQueryAll');
    Route::put('official', 'OfficialController@update');
    Route::get('fetch_all_officials', 'OfficialController@fetchAll');
});
