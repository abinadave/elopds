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
    Route::get('province', 'ProvinceController@fetchProvinces');
    Route::get('citymun', 'ProvinceController@fetchCityMuns');
    Route::post('search_query_province', 'OfficialController@searchQueryProvince');
    Route::post('search_query_citymun', 'OfficialController@searchQueryCItyMun');
    Route::post('get_relared_mun', 'ProvinceController@fetchRelatedCityMun');
    Route::post('search_query_all', 'OfficialController@searchQueryAll');
    Route::put('official', 'OfficialController@update');
    Route::get('fetch_all_officials', 'OfficialController@fetchAll');
    Route::get('importExport', 'MaatwebsiteDemoController@importExport');
    Route::post('importExcel', 'OfficialController@importExcel');
    Route::get('import_officials', 'HomeController@showImportView');
    Route::post('fileuploader', 'OfficialController@importFile');
    Route::post('get_officials_by_citymun', 'OfficialController@fetchByCityMun');
    Route::get('lgus', 'OfficialController@fetchAllLgus');
    Route::post('get_officials_by_province', 'OfficialController@fetchByProvince');
    Route::get('fetch_all_officials_report_by_province', 'OfficialController@fetchReportByProvince');
});

Route::post('importExcel', 'OfficialController@importExcel');
