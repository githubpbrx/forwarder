<?php

use Illuminate\Support\Facades\Route;

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

Route::prefix('master')->group(function () {
    Route::get('/', 'MasterController@index');

    Route::group(['prefix' => 'forwarder'], function () {
        Route::get('/', 'MasterForwarder@index')->name('masterforwarder');
        Route::get('/getkaryawan/{id}', 'MasterForwarder@getkaryawan')->name('getkaryawan');
        Route::get('/listforwarder', 'MasterForwarder@listforwarder')->name('list_forwarder');
        Route::post('/fwdedit', 'MasterForwarder@editfwd')->name('masterfwd_edit');
        Route::post('/savefwd', 'MasterForwarder@savefwd')->name('masterfwd_save');
        Route::post('/updatefwd', 'MasterForwarder@updatefwd')->name('masterfwd_update');
        Route::get('/deletefwd/{id}', 'MasterForwarder@destroyfwd')->name('masterfwd_delete');
    });

    Route::group(['prefix' => 'hscode'], function () {
        Route::get('/', 'MasterHscode@index')->name('masterhscode');
        Route::get('/listhscode', 'MasterHscode@listhscode')->name('list_hscode');
        Route::post('/hscodeadd', 'MasterHscode@add')->name('masterhscode_add');
        Route::post('/hscodeedit', 'MasterHscode@edit')->name('masterhscode_edit');
        Route::post('/updatehscode', 'MasterHscode@update')->name('masterhscode_update');
        Route::get('/deletehscode/{id}', 'MasterHscode@destroy')->name('masterhscode_delete');
    });

    Route::group(['prefix' => 'route'], function () {
        Route::get('/', 'MasterRoute@index')->name('masterroute');
        Route::get('/listroute', 'MasterRoute@listroute')->name('list_route');
        Route::post('/routeadd', 'MasterRoute@add')->name('masterroute_add');
        Route::post('/routeedit', 'MasterRoute@edit')->name('masterroute_edit');
        Route::post('/updateroute', 'MasterRoute@update')->name('masterroute_update');
        Route::get('/deleteroute/{id}', 'MasterRoute@destroy')->name('masterroute_delete');
    });

    Route::group(['prefix' => 'pol'], function () {
        Route::get('/', 'MasterPOL@index')->name('masterpol');
        Route::get('/listpol', 'MasterPOL@listpol')->name('list_pol');
        Route::post('/poladd', 'MasterPOL@add')->name('masterpol_add');
        Route::post('/poledit', 'MasterPOL@edit')->name('masterpol_edit');
        Route::post('/updatepol', 'MasterPOL@update')->name('masterpol_update');
        Route::get('/deletepol/{id}', 'MasterPOL@destroy')->name('masterpol_delete');
    });

    Route::group(['prefix' => 'pod'], function () {
        Route::get('/', 'MasterPOD@index')->name('masterpod');
        Route::get('/listpod', 'MasterPOD@listpod')->name('list_pod');
        Route::post('/podadd', 'MasterPOD@add')->name('masterpod_add');
        Route::post('/podedit', 'MasterPOD@edit')->name('masterpod_edit');
        Route::post('/updatepod', 'MasterPOD@update')->name('masterpod_update');
        Route::get('/deletepod/{id}', 'MasterPOD@destroy')->name('masterpod_delete');
    });

    Route::group(['prefix' => 'country'], function () {
        Route::get('/', 'MasterCountry@index')->name('mastercountry');
        Route::get('/listcountry', 'MasterCountry@listcountry')->name('list_country');
        Route::post('/countryadd', 'MasterCountry@add')->name('mastercountry_add');
        Route::post('/countryedit', 'MasterCountry@edit')->name('mastercountry_edit');
        Route::post('/updatecountry', 'MasterCountry@update')->name('mastercountry_update');
        Route::get('/deletecountry/{id}', 'MasterCountry@destroy')->name('mastercountry_delete');
    });

    Route::group(['prefix' => 'polcity'], function () {
        Route::get('/', 'MasterPOL_City@index')->name('masterpolcity');
        Route::get('/listpolcity', 'MasterPOL_City@listpolcity')->name('list_polcity');
        Route::post('/polcityadd', 'MasterPOL_City@add')->name('masterpolcity_add');
        Route::post('/getcountry', 'MasterPOL_City@getcountry')->name('getcountry');
        Route::post('/polcityedit', 'MasterPOL_City@edit')->name('masterpolcity_edit');
        Route::post('/updatepolcity', 'MasterPOL_City@update')->name('masterpolcity_update');
        Route::get('/deletepolcity/{id}', 'MasterPOL_City@destroy')->name('masterpolcity_delete');
    });

    Route::group(['prefix' => 'podcity'], function () {
        Route::get('/', 'MasterPOD_City@index')->name('masterpodcity');
        Route::get('/listpodcity', 'MasterPOD_City@listpodcity')->name('list_podcity');
        Route::post('/podcityadd', 'MasterPOD_City@add')->name('masterpodcity_add');
        Route::post('/getcountry', 'MasterPOD_City@getcountry')->name('getcountry');
        Route::post('/getpolcity', 'MasterPOD_City@getpolcity')->name('getpolcity');
        Route::post('/podcityedit', 'MasterPOD_City@edit')->name('masterpodcity_edit');
        Route::post('/updatepodcity', 'MasterPOD_City@update')->name('masterpodcity_update');
        Route::get('/deletepodcity/{id}', 'MasterPOD_City@destroy')->name('masterpodcity_delete');
    });
});
